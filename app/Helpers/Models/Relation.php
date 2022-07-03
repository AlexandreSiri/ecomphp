<?php

namespace App\Helpers\Models;

/**
 * @method self where(array $data)
 * @method self where(string $key, mixed $value)
 * @method self where(string $key, string $separator, mixed $value)
 */
class Relation {
    private Builder $builder;

    public function __construct(string $class, private array $params) {
        $this->builder = new Builder($class);
        $this->builder->where($params);
    }
    
    public function limit(int $limit): self {
        $this->builder->limit($limit);
        return $this;
    }
    public function orderBy(string $order): self {
        $this->builder->orderBy($order);
        return $this;
    }
    public function orderByDesc(string $order): self {
        $this->builder->orderByDesc($order);
        return $this;
    }
    public function where(array|string $data, mixed $separator = null, mixed $value = null): self {
        $this->builder->where($data, $separator, $value);
        return $this;
    }

    public function run() {
        $data = $this->builder->get();
        return $data;
    }
}