<?php

namespace App\Helpers\Models;

/**
 * @method self where(array $data)
 * @method self where(string $key, mixed $value)
 * @method self where(string $key, string $separator, mixed $value)
 */
class ManyRelation {
    private array $builders = [];

    public function __construct(string $class, array $wheres = []) {
        $this->builders = array_map(fn (array $where) => (new Builder($class))->where($where)->limit(1), $wheres);
    }
    
    public function orderBy(string $order): self {
        foreach ($this->builders as $builder) $builder->orderBy($order);
        return $this;
    }
    public function orderByDesc(string $order): self {
        foreach ($this->builders as $builder) $builder->orderByDesc($order);
        return $this;
    }
    public function where(array|string $data, mixed $separator = null, mixed $value = null): self {
        foreach ($this->builders as $builder) $builder->where($data, $separator, $value);
        return $this;
    }

    public function run() {
        $data = [];
        array_map(function (Builder $builder) use (&$data) {
            $d = $builder->get();
            if ($d) array_push($data, $d);
        }, $this->builders);

        return $data;
    }

}