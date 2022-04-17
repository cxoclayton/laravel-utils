<?php

namespace rccjr\utils\Database\Mappers\Turtle;

use Illuminate\Support\Str;

class TableToTurtle implements \JsonSerializable, \Stringable
{
    protected $iri;
    protected $def;
    protected $fields;
    public function __construct($table, $namespace) {
        $this->iri = $this->formatNamespace($namespace).ucfirst(Str::singular($table['_config']['_name']));
        $this->fields = collect($table['fields'])->map(function($field) {
           return new FieldToTurtle($field, $this->iri);
        });

    }

        protected function formatNamespace($namespace) {
            if(substr($namespace, 0,-1) !== '/') {
                return $namespace."/";
            }
            return $namespace;
        }

    public function def()
    {
        #return "<{$this->iri}> a owl:Class .\n"; #\n\t rdfs:label ".'"'.$this->iri.'"'." .\n";
        return "<{$this->iri}> a owl:Class ;\n\t rdfs:label ".'"'.$this->iri.'"'." .\n";
    }
    public function __toString()
    {
        $str = $this->def();
        $str .= $this->fields->map(function($field)  {
            return (string)$field;
        })->join("\n");

        return $str;

    }

    public function jsonSerialize() : mixed
    {
        return [
            'iri' => $this->iri,
            'fields' => $this->fields()->toArray(),
        ];
    }
}
