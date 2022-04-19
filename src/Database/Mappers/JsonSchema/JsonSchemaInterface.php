<?php

namespace rccjr\utils\Database\Mappers\JsonSchema;

use Illuminate\Support\Collection;

interface JsonSchemaInterface
{
    public function toArray() : array;
    public function getSchemaName() : string;
    public function getSchema() : array;
    public function getDefs() : Collection ;
}
