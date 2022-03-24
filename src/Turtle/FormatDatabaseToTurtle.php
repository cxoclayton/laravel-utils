<?php

namespace rccjr\utils\Turtle;

use rccjr\utils\Database\Factories\DatabaseSchemaFactory;

class FormatDatabaseToTurtle
{

    protected $schema;

    public function __construct($json) {
        $this->schema = $json;

    }
}
