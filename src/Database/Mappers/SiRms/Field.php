<?php

namespace rccjr\utils\Database\Mappers\SiRms;

use rccjr\utils\Database\Column;
use rccjr\utils\Database\Mappers\SiRms\Types;

class Field
{

    public function __construct(Column $field)
    {
        $this->field = $field;
        $this->type = Types::get_type('DB_'.$field->getType());
        $this->typeIsRef = !(stristr($this->type, '$defs') === FALSE);
    }

    public function getType() : string
    {
        return strtoupper($this->type);
    }

    public function getName($quoted = true)
    {
        if (!$quoted) {
            return $this->field->getName();
        }

            return '"'.$this->field->getName().'"';

    }

    public function __toString()
    {
        return 'ATTRIBUTE '.$this->getName().' DATATYPE '.strtoupper($this->getType()).' DISPLAY';
    }
}
