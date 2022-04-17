<?php

namespace rccjr\utils\Database\Mappers\JsonSchema;
use Carbon\CarbonInterface as Carbon;

trait CommonJsonMapper
{


    public function formatDateTime(Carbon $date)
    {
        return $date->toIso8601ZuluString();
    }

    public function formatDate(Carbon $date)
    {
        return $date->format("Y-m-d");
    }

    public function formatBoolean(bool $value)
    {
        return $value;
    }
}
