<?php

namespace rccjr\utils\Mappers\JsonSchema;

class Field implements \JsonSerializable
{

    protected $field;
    protected $type;
    protected $typeIsRef;
    public function __construct($field)
    {
        $this->field = $field;
        $this->type = Types::get_type('DB_'.$field['type']);
        $this->typeIsRef = (stristr($this->type, '$defs') === FALSE);

    }

    public function toArray() {
        $schema = collect();
        if ($this->typeIsRef) {
            $schema->put('$ref', $this->type);
        } else {
            $schema->put('type', $this->type);
        }
        $schema->put('$comments', [$this->field->getDetails()['comment']]);

    }

    public function JsonSerialize() {
        return $this->toArray();
    }
}
