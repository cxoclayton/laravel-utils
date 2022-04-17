<?php

namespace rccjr\utils\Database\Mappers\Turtle;

class FormatDatabaseToTurtle
{

    protected $schema;

    public function __construct($json) {
        $this->schema = $json;

    }
}
