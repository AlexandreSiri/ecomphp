<?php

namespace App\Helpers\Models;

use DateTime;
use ReflectionClass;

/**
 * @method self where(array $data)
 * @method self where(string $key, mixed $value)
 * @method self where(string $key, string $separator, mixed $value)
 */
class Builder {
    private Singleton $pdo;
    private ReflectionClass $class;
    private string $table;
    private bool $isParanoid;

    private ?string $order = null;
    private bool $desc = false;
    private int $limit = -1;
    private array $where = [];
    private bool $paranoid = false;
    
    public function __construct(string $class) {
        $this->class = new ReflectionClass($class);
        $this->table = $this->class->getMethod("getTableName")->invoke(null);
        $this->isParanoid = $this->class->getMethod("isParanoid")->invoke(null);

        $this->pdo = Singleton::getInstance();
    }
    private function formatData(array $data) {
        $response = [];
        foreach ($data as $key => $value) {
            
            if (gettype($key) === "integer" || !$this->class->hasProperty($key)) continue;
            $method = $this->class->getProperty($key);
            
            if ($method->getType() || $value === null) {
                switch ($method->getType()->getName()) {
                    case "int":
                        $value = floatval($value);
                        break;
                    case "bool":
                        $value = !!intval($value);
                        break;
                    case \DateTime::class:
                        $value = $value ? new DateTime($value) : null;
                        if ($value && date_format($value, "Y") === "1000") $value = null;
                        break;
                }
            }
            $response[$key] = $value;
        }
        return $response;
    }
    private function getInstance(?array $brut) {
        if (!$brut) return null;
        $data = $this->formatData($brut);
        $element = $this->class->newInstance();
        foreach ($data as $key => $value) {
            $element->{$key} = $value;
        }
        $element->setDataValues($data);

        return $element;
    }

    public function limit(int $limit): self {
        $this->limit = $limit;
        return $this;
    }
    public function orderBy(string $order): self {
        $this->order = $order;
        $this->desc = false;
        return $this;
    }
    public function orderByDesc(string $order): self {
        $this->order = $order;
        $this->desc = true;
        return $this;
    }
    public function where(array|string $data, mixed $separator = null, mixed $value = null): self {
        if (gettype($data) === "array") {
            foreach ($data as $key => $v) {
                if (gettype($v) === "array") {
                    $k = array_keys($v)[0];
                    $value = array_shift($v);
                    switch ($k) {
                        case ONE_OF:
                            if (!count($value)) break;
                            $q = join(" OR ", array_map(fn () => "$key = ?", $value));
                            array_push($this->where, ["($q)", $value]);
                            break;
                    }
                } else array_push($this->where, [$key, "=", $v]);
            }
            return $this;
        }
        if (gettype($separator) === "integer" && $separator >= 1000) {
            if ($value === null) $value = "NULL";
        } else if ($value === null) {
            $value = $separator;
            $separator = "=";
        }
        array_push($this->where, [$data, $separator, $value]);

        return $this;
    }
    public function paranoid(bool $paranoid = true): self {
        $this->paranoid = $paranoid;
        return $this;
    }
    public function first(array $fields = []): ?Base {
        return $this->limit(1)->get($fields);
    }
    /** @return Base[] */
    public function all(array $fields = []): array {
        return $this->get($fields);
    }
    /** @return (Base | Base[]) */
    public function get(array $fields = []): Base | array | null {
        $fieldString = count($fields) ? join(", ", $fields) : "*";
        $whereString = join(" AND ", array_merge(array_map(function (array $value) {
            if (count ($value) === 2) {
                return "{$value[0]}";
            }
            if (gettype($value[1]) === "integer" && $value[1] >= 1000) {
                switch ($value[1]) {
                    case IS:
                        return "{$value[0]} IS {$value[2]}";
                    case IS_NOT:
                        return "{$value[0]} IS NOT {$value[2]}";
                    case LIKE:
                        return "{$value[0]} LIKE \"{$value[2]}\"";
                    case NOT_LIKE:
                        return "{$value[0]} NOT LIKE \"{$value[2]}\"";
                }
            }
            return "{$value[0]} {$value[1]} ?";
        }, $this->where), (!$this->isParanoid || $this->paranoid) ? [] : ["deletedAt = '1000-01-01 00:00:00.000'"]));

        $query = "SELECT {$fieldString} FROM {$this->table}";
        $query .= strlen($whereString) ? " WHERE {$whereString}" : "";
        $query .= $this->order ? " ORDER BY {$this->order}" : "";
        $query .= $this->desc && $this->order ? " DESC" : "";
        $query .= $this->limit !== -1 ? " LIMIT {$this->limit}" : "";

        $values = [];
        foreach (array_filter($this->where, fn (array $value) => gettype($value[1]) === "string" || $value[1] < 1000) as $value) {
            array_push($values, $value[2]);
        }
        foreach (array_filter($this->where, fn (array $value) => count($value) === 2) as $value) {
            $values = array_merge($values, $value[1]);
        }
        
        $data = $this->pdo->query($query, $values);
        return $this->limit === 1 ? $this->getInstance(array_shift($data)) : array_map(fn (array $d) => $this->getInstance($d), $data);
    }
}