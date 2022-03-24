<?php


namespace rccjr\utils\Database;

use Log;

class Column
{
    protected $details;
    protected $primaryKeys;
    protected $foreignKeys;

    public function __construct($details, $primaryKeyColumns = [], $fkColumns = [])
    {
        $this->details = $details;
        $this->primaryKeys = $primaryKeyColumns;
        $this->foreignKeys = $fkColumns;
    }

    public function getDetails() {
        return $this->details;
    }
    public function toArray()
    {
        $detailArray = $this->details->toArray();
        return [
            "name" =>$this->details->getName(),
            'type' => $this->details->getType()->getName(),
            'unsigned' => $this->details->getUnsigned(),
            'auto_increment' => $this->details->getAutoincrement(),
            "type_abbr" => $this->getAbbreviatedType(),
            "default" => $detailArray['default'],
            "notnull" => $detailArray['notnull'],
            "precision" => $detailArray['precision'],
            "scale" => $detailArray['scale'],
            "fixed" => $detailArray['fixed'],
            "columnDefinition" => $detailArray['columnDefinition'],
            "comment" => $detailArray['comment'],
            "default" => $this->details->getDefault(),
            'primary' => $this->isPrimaryKey(),
            'is_fk' => $this->isForeignKey(),
        ];
    }

    protected function getNameWithIdentifier()
    {
        return ($this->isPrimaryKey() ? sprintf('<fg=white;options=bold>%s</>', '*') :'').$this->details->getName();

    }

    protected function isForeignKey()
    {
        return in_array($this->details->getName(), $this->foreignKeys);
    }

    protected function isPrimaryKey()
    {

        Log::debug('Looking for '.$this->details->getName(), ['searching' => $this->primaryKeys]);
        return in_array($this->details->getName(), $this->primaryKeys);
    }

    public function getAbbreviatedType()
    {

        $str = $this->details->getType()->getName();
        $suffix = ($this->details->getLength() !== null && strlen($this->details->getLength()) > 0 && $this->details->getLength() )!== 0 ? ' ('.$this->details->getLength().')' : "";
        $suffix .= $this->details->getUnsigned() ? " (unsigned)" : "";
        $suffix .= $this->details->getAutoincrement() ? " (auto_inc)" : "";
        return $str.$suffix;
    }
}
