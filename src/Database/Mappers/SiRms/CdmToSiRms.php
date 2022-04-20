<?php

namespace rccjr\utils\Database\Mappers\SiRms;

use Illuminate\Support\Collection;

class CdmToSiRms
{

    protected $defs;
    protected $values;
    protected $output;

    public function __construct(Collection $defs) {
        $this->defs = $defs;
        $this->output = new Collection();
        $this->defs->each(function($item, $name)  {
             if(array_key_exists('type', $item)  ) {
                 $this->output->put($name, $item['type']);
             }
        });
    }

    public function getOutput()
    {
        return $this->output;
    }
    public function parseObject(string $name, array $definition) {
    }

    public function resolveRef(string $ref) {

    }
}
