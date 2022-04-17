<?php

namespace rccjr\utils\Database;

use Illuminate\Support\Facades\DB;
use rccjr\utils\Database\Table;

trait SupportsSchema
{
    protected function getSchemaManager() {
        return $this->getConn->getDoctrineSchemaManager();
    }

    protected function getSchemaTable() : Table {
        return new Table($this->getTable(), $this->getSchemaManager());
    }
}
