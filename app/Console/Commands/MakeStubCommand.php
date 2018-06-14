<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeStubCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:stub {name : Name of the domain model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create file stubs for a new domain model';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Do not break things
        if (! app()->environment('local'))
            return $this->error('Command only available for local environment');

        // Inflect placeholder values
        $this->inflect(trim($this->argument('name')));

        // Do not overwite files
        if ($this->files->exists(app_path("Models/{$this->classSingular}.php")))
            return $this->error('Model already exists');

        // Create file stubs
        $this->createModel()
        ->createMigration()
        ->createMapping()
        ->createRepositoryContract()
        ->createRepository()
        ->createRepositoryBinding();
    }

    /**
     * Inflect the placeholder values.
     *
     * @param  string $original
     * @return self
     */
    protected function inflect($original): self
    {
        $this->singular = Str::camel(Str::singular($original));
        $this->plural = Str::plural($this->singular);
        $this->classSingular = Str::studly($this->singular);
        $this->classPlural = Str::studly($this->plural);

        return $this;
    }

    /**
     * Create the model stub.
     *
     * @return self
     */
    protected function createModel(): self
    {
        $path = app_path("Models/{$this->classSingular}.php");
        $stub = $this->getStub('model');

        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Model created successfully');

        return $this;
    }

    /**
     * Create the migration stub.
     *
     * @return self
     */
    protected function createMigration(): self
    {
        $table = Str::snake($this->plural);
        $path = database_path("migrations/0000_00_00_xxxxxx_create_{$table}_table.php");
        $stub = $this->getStub('migration');

        $this->files->put($path, $this->replacePlaceholders($stub, ['dummies_table' => $table]));
        $this->info('Migration created successfully');

        return $this;
    }

    /**
     * Create the database mapping stub.
     *
     * @return self
     */
    protected function createMapping(): self
    {
        $path = database_path("mappings/{$this->classSingular}.php");
        $stub = $this->getStub('mapping');

        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Database mapping created successfully');

        $path = database_path('mappings/all.php');
        $this->files->append($path, '// TO' . "DO Doctrine\Mappings\\{$this->classSingular}::class,\n");

        return $this;
    }

    /**
     * Create the repository contract stub.
     *
     * @return self
     */
    protected function createRepositoryContract(): self
    {
        $path = app_path("Repositories/Contracts/{$this->classSingular}Repository.php");
        $stub = $this->getStub('repositoryContract');

        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Repository contract created successfully');

        return $this;
    }

    /**
     * Create the repository stub.
     *
     * @return self
     */
    protected function createRepository(): self
    {
        $path = app_path("Repositories/Doctrine/{$this->classSingular}Repository.php");
        $stub = $this->getStub('repository');

        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Repository created successfully');

        return $this;
    }

    /**
     * Create the repository container binding stub.
     *
     * @return self
     */
    protected function createRepositoryBinding(): self
    {
        $path = app_path('Providers/RepositoryServiceProvider.php');
        $stub = $this->getStub('repositoryBinding');

        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Repository binding created successfully');

        return $this;
    }

    /**
     * Get the content of the stub.
     *
     * @param  string $stub
     * @return string
     */
    protected function getStub(string $stub): string
    {
        $path = resource_path("stubs/$stub.stub");

        return $this->files->get($path);
    }

    /**
     * Replace the placeholders for the given stub.
     *
     * @param  string  $stub
     * @param  array   $extraReplacements
     * @return string
     */
    protected function replacePlaceholders(string $stub, array $extraReplacements = []): string
    {
        if ($extraReplacements)
            $stub = str_replace(array_keys($extraReplacements), $extraReplacements, $stub);

        $stub = str_replace(['Dummies', 'dummies'], [$this->classPlural, $this->plural], $stub);

        return str_replace(['Dummy', 'dummy'], [$this->classSingular, $this->singular], $stub);
    }
}
