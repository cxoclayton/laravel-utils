<?php

namespace rccjr\utils\Database\Mappers\SiRms;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SiRmsWriter
{

    protected $properties;
    protected $schema_version = 'https://json-schema.org/draft/2019-09/schema';
    protected $id;
    protected $title = "";
    protected $description = "";
    protected $itemtypes;


    /**
     * @param  string  $id
     * @param  string  $title
     * @param  string  $description
     * @param  string|null  $schema_version
     */
    public function __construct(string $id, string $title = "", string $description = "", string $schema_version = null)
    {
        $this->properties = collect();
        $this->id = $id;
        $this->title = $title;
        $this->description = "";
        $this->itemtypes = collect();
        if ($schema_version !== null) {
            $this->schema_version = $schema_version;
        }
    }

    public function __toString(): string
    {
        $lines = collect();
        $this->itemtypes->each(function($table) use ($lines) {
            $lines->push((string)$table);
        });

        return $lines->join("\n\n");

    }

    public function addSchema(Table $table)
    {
        $this->itemtypes->put($table->getName(false), $table);
    }

    public function getItems()
    {
        return $this->itemtypes;
    }

    public function addDefs($defs)
    {

    }


}
