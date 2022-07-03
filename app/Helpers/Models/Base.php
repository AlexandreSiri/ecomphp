<?php

namespace App\Helpers\Models;

use DateTime;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @method static self where(array $data)
 * @method static self where(string $key, mixed $value)
 * @method static self where(string $key, mixed $separator, mixed $value)
 */
abstract class Base {
    protected static string $table;
    protected static bool $timestamps = true;
    protected static bool $paranoid = false;
    protected static array $primary = ["id"];
    
    public DateTime $createdAt;
    public DateTime $updatedAt;
    public ?DateTime $deletedAt = null;

    private array $dataValues = [];
    
    public static function getTableName() {
        if (isset(static::$table)) return static::$table;
        $parts = explode("\\", static::class);
        $className = strtolower(preg_replace("/((?<=[a-z])[A-Z])/", '_${1}', array_pop($parts)));
        if ($className[-1] === "y") return preg_replace("/y$/", "ies", $className);
        if ($className[-1] === "s") return preg_replace("/s$/", "ses", $className);
        else return "{$className}s";
    }
    public static function isParanoid() {
        return static::$paranoid;
    }
    private static function getBuilder() {
        return new Builder(static::class);
    }
    static function first(array $fields = []){
        if (count($fields) > 0) $fields = array_unique(array_merge($fields, static::$primary));
        return self::getBuilder()->first($fields);
    }
    static function all(array $fields = []) {
        if (count($fields) > 0) $fields = array_unique(array_merge($fields, static::$primary));
        return self::getBuilder()->all($fields);
    }
    static function limit(int $limit): Builder {
        return self::getBuilder()->limit($limit);
    }
    static function orderBy(string $order): Builder {
        return self::getBuilder()->orderBy($order);
    }
    static function orderByDesc(string $order): Builder {
        return self::getBuilder()->orderByDesc($order);
    }
    static function where(array|string $data, mixed $separator = null, mixed $value = null): Builder {
        return self::getBuilder()->where($data, $separator, $value);
    }
    static function paranoid(bool $paranoid = true): Builder {
        return self::getBuilder()->paranoid($paranoid);
    }
    private static function getPrimary(): array {
        $primary = [];
        $reflection = new ReflectionClass(static::class);
        foreach (static::$primary as $name) {
            if ($reflection->hasProperty($name)) {
                if ($reflection->getProperty($name)->getType()->allowsNull()) throw new Exception("Property \"{$name}\" can't be primary and nullable.", 1);
                array_push($primary, $name);
            };
        }

        if (!count($primary)) {
            $modelParts = explode("\\", static::class);
            $modelName = array_pop($modelParts);
            throw new Exception("Model \"{$modelName}\" need at least one primary key.", 1);
        }
        return $primary;
    }
    /** @return \ReflectionProperty[] */
    private static function getConstructor(): array {
        $data = [];
        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            if (
                $property->isStatic() || 
                $property->isProtected() || 
                $property->isPrivate() || 
                $name === "id" || 
                in_array($name, ["createdAt", "updatedAt", "deletedAt"])
            ) continue;
            array_push($data, $property);
        }
        
        return $data;
    }
    private static function getValue(ReflectionProperty $property, mixed $value) {
        $value = match ($property->getType()->getName()) {
            \DateTime::class => $value ? formatDate($value) : null,
            'bool' => $value ? 1 : 0,
            default => $value,
        };
        return $value;
    }
    static function create(array $data) {
        $table = static::getTableName();
        $properties = static::getConstructor();
        $insertions = [];

        foreach ($properties as $property) {
            $name = $property->getName();
            $nullable = $property->getType()->allowsNull();

            if (!$nullable && !isset($data[$name])) {
                throw new Exception("Property \"{$name}\" is required.", 1);
            }
            if (isset($data[$name])) {
                $insertions[$name] = static::getValue($property, $data[$name]);
            }
        }
        if (static::$timestamps) {
            $date = new DateTime();
            $dateStr = formatDate($date);
            $insertions["createdAt"] = $insertions["updatedAt"] = $dateStr;
        }

        $where = [];
        $values = [];
        foreach ($insertions as $key => $value) {
            array_push($where, $key);
            array_push($values, $value);
        }

        $valueString = join(" ", [
            "(" . join(", ", $where) . ")",
            "VALUES (" . join(", ", array_map(fn () => "?", $where)) . ")"
        ]);
        $query = "INSERT INTO {$table} {$valueString}";

        $primary = static::getPrimary();
        $instance = Singleton::getInstance();
        $instance->query($query, $values);

        if (in_array("id", $primary)) {
            return static::getBuilder()->where("id", $instance->lastInsertId())->first();
        } else {
            $values = [];
            foreach ($primary as $key) $values[$key] = $data[$key];
            return static::getBuilder()->where($values)->first();
        }
    }

    public function setDataValues(array $data) {
        return $this->dataValues = $data;
    }
    public function getDataValues(string $key) {
        if (!array_key_exists($key, $this->dataValues)) return null;
        return $this->dataValues[$key];
    }
    public function toArray(): array {
        $data = [];
        $reflection = new ReflectionClass(static::class);
        
        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            if (!isset($this->{$name})) continue;
            $value = $this->{$name};
            switch ($property->getType()->getName()) {
                case \DateTime::class:
                    $value = formatDate($value);
                    break;
            }
            $data[$name] = $value;
        }

        return $data;
    }
    public function isDelete($exception = false): bool {
        $table = static::getTableName();
        if ($exception && !static::$paranoid) throw new Exception("Table \"{$table}\" is not paranoid.", 1);
        else if (!static::$paranoid) return false;
        return !!$this->deletedAt;
    }
    public function save(): void {
        if ($this->isDelete()) return;
        $whereString = join(" AND ", array_merge(array_map(fn (string $key) => "{$key} = ?", static::$primary), static::$paranoid ? ["deletedAt = '1000-01-01 00:00:00.000'"] : []));
        $table = static::getTableName();
        $whereValues = array_map(fn ($key) => $this->{$key}, static::$primary);

        $data = [];
        $values = [];
        foreach (static::getConstructor() as $property) {
            $name = $property->getName();
            // if (!isset($this->{$name})) continue;
            $value = static::getValue($property, $this->{$name});
            array_push($data, "{$name} = ?");
            array_push($values, $value);
        }
        if (static::$timestamps) {
            $this->updatedAt = new DateTime();
            array_push($data, "updatedAt = \"" . formatDate($this->updatedAt) . "\"");
        }

        $dataString = join(", ", $data);
        $values = array_merge($values, $whereValues);
        $query = "UPDATE {$table} SET {$dataString} WHERE {$whereString}";

        Singleton::getInstance()->query($query, $values);
    }
    public function destroy(): void {
        if ($this->isDelete()) return;
        $whereString = join(" AND ", array_map(fn (string $key) => "{$key} = ?", static::$primary));
        $table = static::getTableName();
        $values = array_map(fn ($key) => $this->{$key}, static::$primary);

        if (static::isParanoid()) {
            $deletedAt = new DateTime();
            $deletedAtString = formatDate($deletedAt);
            Singleton::getInstance()->query("UPDATE {$table} SET deletedAt = '{$deletedAtString}' WHERE {$whereString}", $values);
            $this->deletedAt = $deletedAt;
        } else {
            Singleton::getInstance()->query("DELETE FROM {$table} WHERE {$whereString}", $values);
        }
    }
    public function restore(): void {
        if (!$this->isDelete(true)) return;

        $table = static::getTableName();
        $primary = self::getPrimary();
        $primaryValues = [];

        foreach ($primary as $key) array_push($primaryValues, $this->{$key});
        array_push($primary, "deletedAt");
        array_push($primaryValues, formatDate($this->deletedAt));
        
        $whereString = join(" AND ", array_map(fn (string $column) => "$column = ?", $primary));
        $query = "UPDATE {$table} SET deletedAt = '1000-01-01 00:00:00.000' WHERE {$whereString}";
        Singleton::getInstance()->query($query, $primaryValues);
        $this->deletedAt = null;
    }
    private function getColumns(string $class, $columns, $belongs = false) {
        if (!count($columns)) {
            if ($belongs) {
                $parts = explode("\\", $class);
                $className = strtolower(array_pop($parts));
                $name = "{$className}Id";
                $columns["id"] = $this->{$name};
            } else {
                $parts = explode("\\", static::class);
                $className = strtolower(array_pop($parts));
                $name = "{$className}Id";
                $columns[$name] = $this->id;
            }
        }
        return $columns;
    }
    private function getRelation(string $class, array $columns, bool $belongs = false) {
        return new Relation($class, $this->getColumns($class, $columns, $belongs));
    }
    protected function hasOne(string $class, array $columns = []) {
        return $this->getRelation($class, $columns)->limit(1);
    }
    protected function hasMany(string $class, array $columns = []) {
        return $this->getRelation($class, $columns);
    }
    protected function belongsTo(string $class, array $columns = []) {
        return $this->getRelation($class, $columns, true)->limit(1);
    }
    protected function hasManyThrough(string $class, string $table, array $columns1 = [], array $columns2 = []) {
        $columns1 = $this->getColumns(static::class, $columns1);
        $where = [];
        $values = [];
        foreach ($columns1 as $key => $value) {
            array_push($where, $key);
            array_push($values, $value);
        }

        $whereString = join(" AND ", array_map(fn (string $key) => "{$key} = ?", $where));
        $data = Singleton::getInstance()->query("SELECT * FROM {$table} WHERE {$whereString}", $values);

        $columns = [];
        if (!count($columns2)) {
            $parts = explode("\\", $class);
            $className = strtolower(array_pop($parts));
            $columns2 = [
                "{$className}Id" => "id"
            ];
        }
        foreach ($columns2 as $key => $value) $columns[$value] = $key;
        foreach ($columns as $key => $value) $wheres = array_map(fn (array $d) => [$key => $d[$value]], $data);

        return new ManyRelation($class, $wheres);        
    }
    protected function addThrough(?Base $model, string $table, array $columns1 = [], array $columns2 = []) {
        if (!$model) return;
        $columns1 = $this->getColumns(static::class, $columns1);
        $columns2 = $this->getColumns($model::class, $columns2);
        
        $where = [];
        $values = [];
        foreach (array_merge($columns1, $columns2) as $key => $value) {
            array_push($where, $key);
            array_push($values, $value);
        }

        $whereString = join(" AND ", array_map(fn (string $key) => "{$key} = ?", $where));
        $data = Singleton::getInstance()->query("SELECT * FROM {$table} WHERE {$whereString}", $values);

        // if (count($data)) return;

        $valueString = join(" ", [
            "(" . join(", ", $where) . ")",
            "VALUES (" . join(", ", array_map(fn () => "?", $where)) . ")"
        ]);
        $query = "INSERT INTO {$table} {$valueString}";
        Singleton::getInstance()->query($query, $values);
    }
    protected function removeTrough(?Base $model, string $table, array $columns1 = [], array $columns2 = [], bool $all = false) {
        if (!$model) return;
        $columns1 = $this->getColumns(static::class, $columns1);
        if (!count($columns2)) {
            $parts = explode("\\", $model::class);
            $className = strtolower(array_pop($parts));
            $name = "{$className}Id";
            $columns2 = [$name => $model->id];
        }
        
        $where = [];
        $values = [];
        foreach (array_merge($columns1, $columns2) as $key => $value) {
            array_push($where, $key);
            array_push($values, $value);
        }

        $whereString = join(" AND ", array_map(fn (string $key) => "{$key} = ?", $where));
        $limit = $all ? "" : "LIMIT 1";
        Singleton::getInstance()->query("DELETE FROM {$table} WHERE {$whereString} {$limit}", $values);
    }

    public function __get($name) {
        $reflection = new ReflectionClass(static::class);
        $methods = array_filter($reflection->getMethods(), fn (ReflectionMethod $method) => $method->getDeclaringClass()->getName() === static::class);
        if (!$method = array_find(fn (ReflectionMethod $method) => $method->getName() === $name, $methods)) return null;
        if (count($method->getParameters())) return null;
        
        $r = $method->invoke($this);
        return ($r && gettype($r) === "object") ? $r->run() : $r;
    }
    public function __call($name, $arguments) {
        $class = static::class;
        throw new Exception("Attempted to call an undefined method named \"{$name}\" of class \"{$class}\".", 1);
    }
    
}