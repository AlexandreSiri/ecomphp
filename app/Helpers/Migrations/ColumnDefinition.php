<?php

namespace App\Helpers\Migrations;

class ColumnDefinition {
    private bool $primary = false;
    private bool $unique = false;
    private bool $autoIncrement = false;
    private bool $nullable = false;
    private mixed $default = null;

    private ?ForeignConstraint $constraint = null;

    public function __construct(private string $name, private string $type)
    {
    }
    public function isPrimary(): bool {
        return $this->primary;
    }
    public function isAutoIncrement(): bool {
        return $this->autoIncrement;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getConstraint() {
        $c = $this->constraint;
        $this->constraint = null;
        
        return $c;
    }
    private function getDefault(): string {
        if (gettype($this->default) === "string" && str_ends_with("()", $this->default)) return $this->default;
        return "'" . $this->default . "'";
    }
    public function getDefinition(): string {
        return join(" ", array_filter(array(
            $this->name,
            $this->type,
            $this->unique ? "UNIQUE" : null,
            $this->autoIncrement ? "AUTO_INCREMENT" : null,
            $this->nullable ? null : "NOT NULL",
            $this->default !== null ? "DEFAULT " . $this->getDefault() : null
        ), fn ($el) => $el === null ? false : true));
    }

    public function unique(bool $value = true): self {
        $this->unique = $value;
        return $this;
    }
    public function autoIncrement(bool $value = true): self {
        $this->autoIncrement = $value;
        return $this;
    }
    public function nullable(bool $value = true): self {
        $this->nullable = $value;
        return $this;
    }
    public function primary(bool $value = true): self {
        $this->primary = $value;
        return $this;
    }
    public function default(mixed $default): self {
        $this->default = $default;
        return $this;
    }

    public function references(string $column): ForeignConstraint {
        $this->constraint = new ForeignConstraint($this, $column);
        return $this->constraint;
    }
}