<?php

namespace rccjr\utils\Database\Mappers\JsonSchema;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class JsonSchemaWriter implements \JsonSerializable
{
    protected $properties;
    protected $schema_version= 'https://json-schema.org/draft/2019-09/schema';
    protected $id;
    protected $title = "";
    protected $description = "";
    protected $schema;
    protected $defs;

    /**
     * @param  string  $id
     * @param  string  $title
     * @param  string  $description
     * @param  string|null  $schema_version
     */
    public function __construct(string $id, string $title = "",string $description="", string $schema_version = null)
    {
        $this->properties = collect();
        $this->id = $id;
        $this->title = $title;
        $this->description = "";
        $this->schema = collect();
        $this->defs = collect();
        if ($schema_version !== null) {
            $this->schema_version = $schema_version;
        }
    }

    public function addSchema(JsonSchemaInterface $schema)
    {
        $this->properties->put(Str::singular($schema->getSchemaName()), $schema->getSchema());
       $this->addDefs($schema->getDefs());
    }

    public function addSchemaFromArray(array $schema) {
        foreach ($schema as $name => $scheme) {
            $this->properties->put($name, $scheme);
        }
    }

    public function addDefs(Collection $defs) {
        $defs->each(function ($schema, $name) {
            #echo "$name \n";
            $this->defs->put($name, $schema);
        });
    }

    public function toArray() : array {
        return [
            '$schema'=>$this->schema_version,
            '$id' => $this->id,
            '$title' => $this->title,
            '$description' => $this->description,
            'type' => 'object',
            'properties' => $this->properties->toArray(),
            '$defs' => $this->defs->unique()->toArray()
        ];
    }

    public function getProperties() {
        return $this->properties;
    }

    public function getDefs() {
        return $this->defs;
    }

    public function JsonSerialize() : array {
        return $this->toArray();
    }

    public function __toString() : string
    {
        return json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
