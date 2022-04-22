<?php

namespace rccjr\utils\Database\Contracts;

use Illuminate\Database\Eloquent\Model;

interface TableDataMapper
{
    public function mapData(Model $model) : string ;

}
