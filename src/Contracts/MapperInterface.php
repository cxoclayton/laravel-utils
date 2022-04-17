<?php

namespace rccjr\utils\Contracts;

use Carbon\CarbonInterface as Carbon;

interface MapperInterface
{
    public function map($request) : array;
    public function formatDateTime(Carbon $date);
    public function formatDate(Carbon $date);
    public function formatBoolean(bool $value);
}
