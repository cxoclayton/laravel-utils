<?php

namespace rccjr\utils\Database\Mappers\SiRms;

use Illuminate\Database\Eloquent\Model;
use rccjr\utils\Database\SupportsSchema;

class TableToSiRms
{
    protected $schema;
    protected $model;
    protected $lines;
    protected $dataItemName;

    public function __construct(Model $model, string $alias = null)
    {
        $this->lines = collect();
        $this->schema = $model->tableSchema();
        $this->model = $model;
        $this->dataItemName = $alias !== null?$alias:strtolower(class_basename($this->model) );
        $this->buildAttributeLines();
    }

    public function __toString() {
        $str = 'DATAITEM "'.$this->dataItemName."\"\n";

        $str .= $this->lines->filter(function($i) {
            return $this->isEmptyValue($i);
        })->join("\n");

        return $str.= "\nDATAITEM";
    }

    protected function buildAttributeLines() {
        $this->lines = $this->schema->listSchemaColumns()->map(function($column) {

            $name = $column->getName();
            $value = $this->model->$name;
            if($value !== null && strlen($value) > 0)
                return "\t".'ATTRIBUTE "'.$name.'" VALUE '.Types::format($column->getType(), $this->model->$name);
            else
                return null;
        });
    }

    public function addLine($line) {
        $this->lines->push($line);
    }

    public function addAttributeLine(string $attrName, $attrValue) {
        if($this->isEmptyValue($attrValue))
            $this->lines->push("\t".'ATTRIBUTE "'.$attrName.'" VALUE '.$attrValue);
    }

    protected function isEmptyValue($i) {
        return ($i &&
            $i !== null &&
            $i !== false
            && $i != 0
            && strlen($i) > 0);
    }

}
