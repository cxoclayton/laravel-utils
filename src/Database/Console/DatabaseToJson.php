<?php


namespace rccjr\utils\Database\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use rccjr\utils\Database\Column;

class DatabaseToJson extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =
        'rccjr:db
    {--t|table=* : The list of tables you want to view.}
    {--f|output-format= : Choose the output format (json, uml), default is standard out. }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will parse each table in the database and build a json file from it.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function preSetup()
    {
        $this->databaseName = DB::connection()->getDatabaseName();
        $this->details = collect();
        $this->db = DB::connection()->getDoctrineSchemaManager();
        $this->tableNames = collect($this->db->listTableNames());
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
    }

    public function handle()
    {
        $this->preSetup();
        $this->selectedTables = collect($this->option('table'));
        if ($this->selectedTables->count() > 0) {
            $this->collectDetailsForTables($this->selectedTables);
        } else {
            $this->collectDetailsForTables($this->tableNames);
        }

        if ($this->option('output-format') === null) {
            $this->displayInlineDetails();
        }


        return 0;
    }


    protected function displayInlineDetails()
    {
        foreach ($this->details as $table => $tableDetails) {
            $tableStr = sprintf("<fg=white;options=bold>%s</>", $table);
            try {
                $pKeys = collect($tableDetails->getPrimaryKeyColumns())->map(function ($i) {
                    return $i->getName();
                })->toArray();
            } catch(\Exception $e) {
                Log::alert('Error getting primary keys.', ['table' => $tableDetails]);
                $pKeys = [];
            }

            $this->line($tableStr);
            $headers = [
                "name",
                "type",
                "default",
                "notnull",
                "precision",
                "scale",
                "fixed",
                "columnDefinition",
                "comment"
            ];

            $columns =
                collect($tableDetails->getColumns())->map(function ($details) use ($pKeys) {
                    return (new Column($details, $pKeys))->toArray();
                });
            $this->table($headers, $columns->toArray());
        }

    }

    protected function buildJsonSchema()
    {

    }

    protected function collectDetailsForTables($tables) {
        $tables->each(function ($table) {
            if ($this->db->tablesExist([$table])) {
                $this->details->put($table, $this->db->listTableDetails($table));
            } else {
                $this->error("Table $table does not exist");
            }
        });

    }
}
