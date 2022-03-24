<?php


namespace rccjr\utils\Database\Factories;


use Illuminate\Support\Facades\DB;
use rccjr\utils\Database\Table;

class DatabaseSchemaFactory implements \JsonSerializable
{

    protected $databaseName;
    protected $tableNames;
    protected $details;
    protected $primaryKeys;
    protected $db;

    public function __construct($connection)
    {
        $this->primaryKeys = collect();
        $this->databaseName = DB::connection()->getDatabaseName();
        $this->details = collect();
        $this->db = $connection;
        $this->tableNames = collect($this->db->listTableNames());
      /*
        $this->columnFields = [
            "name",
            "typeStr",
            "default",
            "notnull",
            "length",
            "precision",
            "scale",
            "fixed",
            "unsigned",
            "autoincrement",
            "columnDefinition",
            "comment"
        ];
      */
        $this->collectDetailsForTables();

    }

    public function JsonSerialize() : mixed {
        return $this->toArray();
    }
    public function toArray() {
        return [
            'config' => [
                'items' => $this->tableNames->toArray()
                ],
            'schemas' => $this->details->map(function($table) {
                return $table->toArray();
            })->toArray()
        ];
    }
    public function listSchemaDetails()
    {
        return $this->details;
    }

    protected function collectDetailsForTables() {
        $this->tableNames->each(function ($table) {
            if ($this->db->tablesExist($table)) {
                $this->details->put($table, $this->getTableDetails($table));
            } else {
                $this->error("Table $table does not exist");
            }
        });

    }

    public function getTableDetails($table)
    {
        if ($this->db->tablesExist($table)) {
            return new Table($table, $this->db);
        } else {
            throw new \Exception("No table [$table] found.");
        }
    }
}
