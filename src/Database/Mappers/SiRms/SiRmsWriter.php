<?php

namespace rccjr\utils\Database\Mappers\SiRms;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SiRmsWriter
{

    protected $properties;
    protected $schema_version = 'https://json-schema.org/draft/2019-09/schema';
    protected $id;
    protected $title = "";
    protected $description = "";
    protected $itemtypes;

    protected $extra;

    /**
     * @param  string  $id
     * @param  string  $title
     * @param  string  $description
     * @param  string|null  $schema_version
     */
    public function __construct(string $id, string $title = "", string $description = "", string $schema_version = null)
    {
        $this->properties = collect();
        $this->id = $id;
        $this->title = $title;
        $this->description = "";
        $this->itemtypes = collect();
        $this->extra = '';
        if ($schema_version !== null) {
            $this->schema_version = $schema_version;
        }
    }

    public function __toString(): string
    {
        $lines = collect();
        $this->itemtypes->each(function($table) use ($lines) {
            $lines->push((string)$table);
        });

        return $lines->join("\n\n").$this->extra;

    }

    public function addCdm(Collection $defs, array $context) {
        $f = $defs;
        $f->merge($context);
        $this->addCdmToExtra($f);
    }

    public function addSchema(Table $table)
    {
        $this->itemtypes->put($table->getName(false), $table);
    }

    public function getItems()
    {
        return $this->itemtypes;
    }

    public function addDefs($defs)
    {

    }

    public function addCdmToExtra(Collection $types) {
        $lines = $types->map(function($definition, $name) use($types) {
            if(array_key_exists('type', $definition) ) {
                if($definition['type'] === 'object') {
                    $ls = collect()->push('ITEMTYPE '.strtoupper($name) );
                    if(array_key_exists('properties', $definition)) {
                        foreach($definition['properties'] as $name => $property) {
                            $line = "\t".'ATTRIBUTE "'.strtoupper($name).'" ';
                            if(array_key_exists('type', $property)) {
                                $line .= "DATATYPE ".strtoupper($property['type']);
                            }elseif(array_key_exists('$ref', $property) ) {
                                $f = collect(explode('/', $property['$ref']))->last();
                                $t = $types[$f];
                                if(array_key_exists('type', $t)) {
                                    if($this->checkForDate($property) ) {
                                        $line .= "DATATYPE DATE";
                                    } else {

                                        $line .= 'DATATYPE '.(Types::get_type($t['type']) );
                                    }
                                } else {
                                    $line .= 'DATATYPE UNKNOWN';
                                }
                            }
                            $ls->push($line);
                        }

                        $ls->push('END_ITEMTYPE');
                    }
                    return $ls->join("\n");
                } else {
                    return '! Assumed irrelevant ITEMTYPE "'.strtoupper($name).'" DATATYPE '.$definition['type'];
                }
            } elseif (array_key_exists('$ref', $definition)) {
                return 'ITEMTYPE '.strtoupper($name).' DATATYPE '.$defintion['$ref'];
            }
        });

        $this->extra .= $lines->join("\n\n");
    }

    public function checkForDate($values) {
        if(array_key_exists('format', $values) && $values['format'] == 'date')
            return true;
        if(array_key_exists('$ref', $values) && stripos($values['$ref'], 'date') !== false)
            return true;

        return false;
    }


}
