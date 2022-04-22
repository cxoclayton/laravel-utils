<?php

namespace rccjr\utils\Database;

use Illuminate\Support\Facades\DB;
use rccjr\utils\Database\Table;

trait SupportsSchema
{
    protected function getSchemaManager() {
        return $this->getConnection()->getDoctrineSchemaManager();
    }

    public function getSchemaTable() : Table {
        return new Table($this->getTable(), $this->getSchemaManager());
    }

    public static function tableSchema() {
        return (new static)->getSchemaTable();
    }
}
