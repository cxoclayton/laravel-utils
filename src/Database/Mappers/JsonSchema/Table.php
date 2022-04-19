<?php

namespace rccjr\utils\Database\Mappers\JsonSchema;

use Carbon\CarbonInterface as Carbon;
use Illuminate\Support\Collection;
use rccjr\utils\Contracts\MapperInterface;
use \rccjr\utils\Database\Table as DatabaseTable;

class Table implements \JsonSerializable, JsonSchemaInterface
{
    use CommonJsonMapper;

    protected $table;
    protected $columns;
    protected $types;
    protected $required;

    /**
     * @param  \rccjr\utils\Database\Mappers\JsonSchema\Table  $table
     */
    public function __construct(DatabaseTable $table) {
        $this->table = $table;
        $this->columns = collect();
        $this->types = collect();
        $this->required = collect();
        $this->parseColumns();
    }

   public function getColumns() {
        return $this->columns;
   }

    protected function parseColumns() {
        $this->table->getColumns()->each(function($column, $name) {
            $this->columns->put($name, new Field($column));
            if ($column->isRequired()) {
                $this->required->push($name);
            }
        });
    }

    public function getProperties() : Collection {
        return $this->columns;
    }

    public function getDefs() : Collection {
        return Types::getStandardDefs();
    }
    /**
     * @return array
     */
    public function toArray() : array {
        return [
            'type' => 'object',
            'properties' => $this->columns->toArray(),
            '$defs' => $this->getDefs()
        ];
    }

    public function jsonSerialize() : array
    {
      return $this->toArray();
    }

    public function getSchemaName() : string
    {
        return $this->table->getName();
    }

    public function getSchema() : array {
        return [
            'type' => 'object',
            'properties' => $this->columns->toArray()
            ];
    }


}
