<?php

namespace rccjr\utils\Database\Mappers\JsonSchema;

use rccjr\utils\Database\Column;

class Field implements \JsonSerializable
{

    protected $field;
    protected $type;
    protected $typeIsRef;
    public function __construct(Column $field)
    {
        $this->field = $field;
        $this->type = Types::get_type('DB_'.$field->getType());
        $this->typeIsRef = !(stristr($this->type, '$defs') === FALSE);

    }

    public function toArray() {
        $schema = collect();
        $jsonTypes = collect();
        $comments = collect();
        if ($this->typeIsRef) {
            $schema->put('$ref', $this->type);
            $jsonTypes->push('string');
        } else {
            $jsonTypes->put('type', $this->type);
        }

        if (!$this->field->getDetails()->getNotNull()) {
            $jsonTypes->push("null");
            $comments->push("Can be null.");
        }

        $comment = $this->field->toArray()['comment'];
        if ($comment !== null && strlen($comment) > 0) {
            $comments->push($comment);
        }

        if ($comments->count() > 0) {
            $schema->put('$comments', $comments->values() );
        }

        if ($jsonTypes->count() == 1) {
            $schema->put('type', $jsonTypes->first());
        } elseif($jsonTypes->count() > 1) {
            $schema->put('type', $jsonTypes->values() );
        }
        return $schema->toArray();

    }

    public function JsonSerialize() : array {
        return $this->toArray();
    }
}
