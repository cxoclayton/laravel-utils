<?php


namespace rccjr\utils\Database\Factories;


use Illuminate\Support\Facades\DB;
use rccjr\utils\Database\Table;
use Illuminate\Database\ConnectionInterface;

class DatabaseSchemaFactory implements \JsonSerializable
{

    protected $databaseName;
    protected $tableNames;
    protected $details;
    protected $primaryKeys;
    protected $db;

    /**
     * @param  ConnectionInterface|null  $connection
     * @return static
     */
    public static function create(ConnectionInterface $connection = null)
    {
        return new static($connection);
    }

    /**
     * @param  ConnectionInterface|null  $connection
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct(ConnectionInterface $connection = null)
    {
        if ($connection != null) {
            $this->db = $connection->getDoctrineSchemaManager();
        } else {
            $this->db = DB::connection()->getDoctrineSchemaManager();
        }

        $this->primaryKeys = collect();
        $this->databaseName = DB::connection()->getDatabaseName();
        $this->details = collect();

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

    /**
     * @return mixed
     */
    public function JsonSerialize() : mixed {
        return $this->toArray();
    }

    /**
     * @return array
     */
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

    /**
     * @return \Illuminate\Support\Collection
     */
    public function listSchemaDetails()
    {
        return $this->details;
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    protected function collectDetailsForTables() {
        $this->tableNames->each(function ($table) {
            if ($this->db->tablesExist($table)) {
                $this->details->put($table, $this->getTableDetails($table));
            } else {
                $this->error("Table $table does not exist");
            }
        });

    }

    /**
     * @param $table
     * @return Table
     * @throws \Doctrine\DBAL\Exception
     */
    public function getTableDetails(string $table)
    {
        if ($this->db->tablesExist($table)) {
            return new Table($table, $this->db);
        } else {
            throw new \Exception("No table [$table] found.");
        }
    }
}
