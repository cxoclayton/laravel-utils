<?php

namespace rccjr\utils\Mappers\JsonSchema;

use Carbon\CarbonInterface as Carbon;
use rccjr\utils\Contracts\MapperInterface;

class Table implements \JsonSerializable
{
    use CommonJsonMapper;



    public function toArray() {

    }

    public function jsonSerialize()
    {
      return $this->toArray();
    }


}
