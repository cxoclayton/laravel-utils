<?php


namespace rccjr\utils\Database\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use rccjr\utils\Database\Column;
use rccjr\utils\Database\Factories\DatabaseSchemaFactory;

class DatabaseToTurtle extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rccjr:schematoturtle {namespace=http://rccjr.us/}
    {--t|table=* : The list of tables you want to view.}
    {--o|output= : Choose the output path, default is standard out. }

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
        $this->db = DB::connection()->getDoctrineSchemaManager();
        $this->databaseName = DB::connection()->getDatabaseName();
    }


    public function handle()
    {
        $namespace = $this->formatNamespace($this->argument('namespace') );
        $this->info('Namespace:'.$namespace);
        $this->info('Databse:'.$this->databaseName);
        $schemaFactory = new DatabaseSchemaFactory($this->db);
        $this->line(json_encode($schemaFactory, JSON_PRETTY_PRINT));
        return 0;
    }

    protected function formatNamespace($namespace) {
        if(substr($namespace, 0,-1) !== '/') {
            return $namespace."/";
        }
        return $namespace;
    }


}
