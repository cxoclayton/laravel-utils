<?php

namespace rccjr\utils\Database;

use Illuminate\Support\Collection;
use rccjr\utils\Database\Column;

class Table
{
    protected $name;
    protected $pKeys;
    protected $relationships;
    protected $db;
    protected $columns;

    /**
     * Returns the table name.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the list of private keys
     * @return \Illuminate\Support\Collection
     */
    public function getPrivateKeys(): \Illuminate\Support\Collection
    {
        return $this->pKeys;
    }


    /**
     * Returns a Collection of Columns;
     * @return \Illuminate\Support\Collection
     * @see \rccjr\utils\Database\Column
     */
    public function getColumns(): \Illuminate\Support\Collection
    {
        return $this->columns;
    }


    /**
     * @param  string  $name
     * @param $conn : The connection.
     */
    public function __construct(string $name, $conn)
    {
        $this->name = $name;
        $this->db = $conn;
        $this->relationships = $this->getRelationships();
        $this->pKeys = $this->getDetails()->hasPrimaryKey()?collect($this->getDetails()->getPrimaryKey()->getColumns()):collect();
        $this->columns = collect($this->getDetails()->getColumns())->map(function ($details) {
            return (new Column($details, $this->pKeys->toArray(), $this->getRelationships()->keys()->toArray()));
        });

    }

    /**
     * Returns a collection of
     * @return Collection
     */
    public function listSchemaColumns() : Collection {
        return $this->columns;
    }
    public function getDetails()
    {
        return $this->db->listTableDetails($this->name);
    }

    public function toArray()
    {
        return
        [
            '_config' => [
                '_name' => $this->name,
                '_identifier' => $this->pKeys->first(),

            ],
            'relationships'=> $this->getRelationships()->toArray(),
            'fields' => $this->columns->map(function($c) { return $c->toArray(); })->toArray()
        ];
    }

    protected function getRelationships()
    {
        return collect($this->db->listTableForeignKeys($this->name))->map(function ($fk) {
            return [
                'field' => collect($fk->getLocalColumns())->first(),
                'refrences' =>collect( $fk->getForeignColumns())->first(),
                'on' => $fk->getForeignTableName(),

            ];
        })->keyBy('field');
    }
}
