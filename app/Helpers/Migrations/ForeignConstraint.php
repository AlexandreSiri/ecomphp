<?php

namespace App\Helpers\Migrations;

class ForeignConstraint {
    private string $column;
    private ?string $foreignTable = null;
    private ?string $delete = "CASCADE";
    private ?string $update = null;
    private ?ColumnDefinition $columnDef = null;

    public function __construct(ColumnDefinition|string $column, private ?string $foreignColumn = null) {
        if (gettype($column) === "string") {
            $this->column = $column;
        } else {
            $this->column = $column->getName();
            $this->columnDef = $column;
        }
    }

    public function references(string $column): self {
        $this->foreignColumn = $column;
        return $this;
    }
    public function on(string $table): self {
        $this->foreignTable = $table;
        return $this;
    }
    public function cascadeOnUpdate(): self {
        $this->update = "CASCADE";
        return $this;
    }
    public function restrictOnUpdate(): self {
        $this->update = "RESTRICT";
        return $this;
    }
    public function cascadeOnDelete(): self {
        $this->delete = "CASCADE";
        return $this;
    }
    public function restrictOnDelete(): self {
        $this->delete = "RESTRICT";
        return $this;
    }
    public function nullOnDelete(): self {
        $this->delete = "SET NULL";
        return $this;
    }
    public function primary(bool $value = true): self {
        if ($this->columnDef) $this->columnDef->primary($value);
        return $this;
    }

    
    public function getColumn(): string {
        return $this->column;
    }
    public function getForeignTable() {
        return $this->foreignTable;
    }
    public function getForeignColumn() {
        return $this->foreignColumn;
    }
    public function getConstraint(): string {
        $constraint = "";
        if ($this->update) $constraint.=" ON UPDATE " . $this->update;
        if ($this->delete) $constraint.=" ON DELETE " . $this->delete;

        return $constraint;
    }
    
}