<?php

namespace App\Helpers\Migrations;
use Exception;

class Blueprint {
    /**
     * @var ColumnDefinition[]
     */
    private array $fields = [];
    /**
     * @var ForeignConstraint[]
     */
    private array $foreigns = [];

    public function __construct(private string $table)
    {
    }
    
    private function add(ColumnDefinition $field) {
        array_push($this->fields, $field);
    }
    public function checkForeign() {
        array_map(function (ColumnDefinition $field) {
            $constraint = $field->getConstraint();
            if (!$constraint) return;
            
            array_push($this->foreigns, $constraint);
        }, $this->fields);

        $entries = array_count_values(array_map(function (ForeignConstraint $fn) {
            $key = $fn->getColumn();

            if ($fn->getForeignColumn() === null) stop("Foreign key \"{$key}\" has no foreign column.", true);
            if ($fn->getForeignTable() === null) stop("Key \"{$key}\" has no foreign table.", true);

            return $fn->getColumn();
        }, $this->foreigns));
        foreach ($entries as $key => $value) {
            if ($value > 1) stop("Key \"{$key}\" has \"{$value}\" constraints.", true);
        }
    }
    public function checkPrimary() {
        $primaries = array_filter($this->fields, fn (ColumnDefinition $column) => $column->isPrimary());
        if (!count($primaries)) stop("Table \"{$this->table}\" need to contains at least 1 primary key.", true);
    }
    public function foreign(string $column): ForeignConstraint {
        $foreign = new ForeignConstraint($column);
        array_push($this->foreigns, $foreign);

        return $foreign;
    }

    public function id(): ColumnDefinition {
        return $this->integer("id")->unique()->primary()->autoIncrement();
    }
    public function timestamps(bool $paranoid = false) {
        $this->dateTime("createdAt");
        $this->dateTime("updatedAt");
        $paranoid && $this->dateTime("deletedAt")->default("1000-01-01");
    }

    
    public function integer(string $name) {
        $column = new ColumnDefinition($name, "INTEGER");
        
        $this->add($column);
        return $column;
    }
    public function float(string $name) {
        $column = new ColumnDefinition($name, "FLOAT");
        
        $this->add($column);
        return $column;
    }


    public function string(string $name, int $length = 255) {
        if ($length > 16383) throw new Exception("Colmun \"string\" can contains at least \"16383\" characters, use \"text\" or \"longText\" instead.", 1);
        $column = new ColumnDefinition($name, "VARCHAR($length)");
        
        $this->add($column);
        return $column;
    }
    public function text(string $name) {
        $column = new ColumnDefinition($name, "TEXT");
        
        $this->add($column);
        return $column;
    }
    public function longText(string $name) {
        $column = new ColumnDefinition($name, "LONGTEXT");
        
        $this->add($column);
        return $column;
    }


    public function boolean(string $name) {
        $column = new ColumnDefinition($name, "TINYINT(1)");
        
        $this->add($column);
        return $column;
    }

    
    public function dateTime(string $name) {
        $column = new ColumnDefinition($name, "DATETIME(3)");
        
        $this->add($column);
        return $column;
    }
    public function date(string $name) {
        $column = new ColumnDefinition($name, "DATE");
        
        $this->add($column);
        return $column;
    }

    
    public function getScript(string $table): string {
        $deletedAt = array_find(fn (ColumnDefinition $f) => $f->getName() === "deletedAt", $this->fields);
        $primaryColumns = array_filter($this->fields, fn (ColumnDefinition $f) => $f->isPrimary());
        if ($deletedAt && !array_find(fn (ColumnDefinition $f) => $f->isAutoIncrement(), $primaryColumns)) {
            $deletedAt->primary();
        }

        $primary = join(", ", array_map(fn (ColumnDefinition $f) => $f->getName(), array_filter($this->fields, fn (ColumnDefinition $f) => $f->isPrimary())));
        $foreign = array_map(function (ForeignConstraint $constraint) {
            $column = $constraint->getColumn();
            $foreignTable = $constraint->getForeignTable();
            $foreignColumn = $constraint->getForeignColumn();

            return "FOREIGN KEY ($column) REFERENCES $foreignTable($foreignColumn) " . $constraint->getConstraint();
        }, $this->foreigns);
        $fields = join(", ", array_merge(
            array_map(fn (ColumnDefinition $f) => $f->getDefinition(), $this->fields),
            array_filter(array(strlen($primary) ? "PRIMARY KEY ($primary)" : null), fn ($el) => $el === null ? false : true),
            $foreign
        ));
        return "CREATE TABLE $table ($fields)";
    }
}