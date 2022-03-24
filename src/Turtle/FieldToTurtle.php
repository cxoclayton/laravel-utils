<?php

namespace rccjr\utils\Turtle;

class FieldToTurtle implements \JsonSerializable,\Stringable
{
/*
 *
<http://poi.va.gov/v1/class#id> a owl:DatatypeProperty ;
    rdfs:domain <http://poi.va.gov/v1/class#Person> ;
    rdfs:range xsd:string
 */
    protected $turtleNamespace;
    protected $type;
    protected $domain;

    public function __construct($field, $tableNamespace) {
       $this->turtleNamespace = $tableNamespace."#".$field['name'];
       $this->type = Types::get_type('DB_'.$field['type']);
       $this->domain = $tableNamespace;
   }

   public function toArray() {
        return [
            'iri' => $this->turtleNamespace,
            'domain' => $this->domain,
            'range' => $this->type
        ];
   }

   public static function asTurtleString($iri, $range, $domain = null) {
       $str =  "<{$iri}> a owl:DatatypeProperty ;\n\trdfs:range {$range} ";
        if($domain) {
           $str .= ";\n\trdfs:domain <{$domain}> ";
        }

        return $str.' .';
   }


    public function __toString()
    {
        return self::asTurtleString($this->turtleNamespace, $this->type, $this->domain);
            #TODO setup ability to pass to multiple domains;

    }

    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
