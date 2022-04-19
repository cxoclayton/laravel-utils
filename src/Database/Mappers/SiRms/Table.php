<?php

namespace rccjr\utils\Database\Mappers\SiRms;

use rccjr\utils\Database\Table as DatabaseTable;
use rccjr\utils\Database\Mappers\SiRms\Field as Field;
use Illuminate\Support\Str;

class Table
{

    protected $table;
    protected $columns;
    protected $types;
    protected $required;

    public function __construct(DatabaseTable $table) {
        $this->table = $table;
        $this->columns = collect();
        $this->types = collect();
        $this->required = collect();
        $this->parseColumns();
    }

    protected function parseColumns()
    {
        $this->table->getColumns()->each(function($column, $name) {
            $this->columns->put($name, new Field($column));
            if ($column->isRequired()) {
                $this->required->push($name);
            }
        });
    }

    public function getName($quoted = true)
    {
        if (!$quoted) {
            return $this->table->getName();
        }

        return '"'.$this->table->getName().'"';

    }

    public function __toString() : string
    {
       $lines = collect();
       $lines->push('ITEMTYPE "'.Str::singular($this->getName(false)).'"' );
       $this->columns->each(function($field) use ($lines) {
           $lines->push("\t".(string)$field);
       });
       $lines->push('END_ITEMTYPE');

       return $lines->join("\n");
    }
}
