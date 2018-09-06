<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeStub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:stub {name : Name of the domain model} {--M|module=master}';

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
     * Create a new command instance.
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
        $this->inflect(trim($this->argument('name')), trim($this->option('module')));

        // Do not overwite files
        if ($this->files->exists(app_path("Models/{$this->singularClass}.php")))
            return $this->error('Model already exists');

        // Create file stubs
        $this
        ->createModule()
        ->createModel()
        ->createMigration()
        ->createMapping()
        ->createRepositoryContract()
        ->createRepository()
        ->createRepositoryBinding()
        ->createPolicy()
        ->createRoute()
        ->createController()
        ->createFormRequests()
        ->createResourceViews()
        ->createSubmodule()
        ->createFeatureTest();
    }

    /**
     * Create new module.
     *
     * @return self
     */
    protected function createModule(): self
    {
        $path = app_path("Http/Controllers/{$this->moduleClass}");

        // If the module already exists, skip
        if (is_dir($path))
            return $this;

        $this->files->makeDirectory($path);
        $path = resource_path("views/modules/{$this->module}");
        $this->files->makeDirectory($path);

        $path = resource_path('views/top.blade.php');
        $stub = $this->getStub('top');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Top navigation view updated successfully');

        $path = resource_path("views/modules/{$this->module}.blade.php");
        $stub = $this->getStub('module');
        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Module view created successfully');

        $path = resource_path('views/home.blade.php');
        $stub = $this->getStub('home');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Home view updated successfully');

        $path = app_path('Policies/ModulePolicy.php');
        $stub = $this->getStub('modulePolicy');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Module policy updated successfully');

        $path = database_path('seeds/PermissionsSeeder.php');
        $stub = $this->getStub('permissionsSeederModule');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Permissions seeder updated successfully');

        $path = base_path('routes/web.php');
        $stub = $this->getStub('routeModule');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Routes updated successfully');

        $path = base_path("tests/Feature/{$this->moduleClass}ModuleTest.php");
        $stub = $this->getStub('tests/feature/module');
        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Module feature tests created successfully');

        return $this;
    }
    /**
     * Create the model stub.
     *
     * @return self
     */
    protected function createModel(): self
    {
        $path = app_path("Models/{$this->singularClass}.php");
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
        $path = database_path("mappings/{$this->singularClass}.php");
        $stub = $this->getStub('mapping');
        $this->files->put($path, $this->replacePlaceholders($stub));

        $path = database_path('mappings/all.php');
        $this->files->append($path, '// TO' . "DO Add to the mappings array Doctrine\Mappings\\{$this->singularClass}::class,\n");
        $this->info('Database mapping created successfully');

        return $this;
    }

    /**
     * Create the repository contract stub.
     *
     * @return self
     */
    protected function createRepositoryContract(): self
    {
        $path = app_path("Repositories/Contracts/{$this->singularClass}Repository.php");
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
        $path = app_path("Repositories/Doctrine/{$this->singularClass}Repository.php");
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
        $this->info('Repository binding updated successfully');

        return $this;
    }

    /**
     * Create the gate policy stub.
     *
     * @return self
     */
    protected function createPolicy(): self
    {
        $path = app_path("Policies/{$this->singularClass}Policy.php");
        $stub = $this->getStub('policy');

        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Gate policy created successfully');

        $path = app_path('Providers/AuthServiceProvider.php');
        $stub = $this->getStub('policyMapping');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Gate policy mapping updated successfully');

        $path = database_path('seeds/PermissionsSeeder.php');
        $stub = $this->getStub('permissionsSeeder');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Permissions seeder updated successfully');

        return $this;
    }

    /**
     * Create the route and the route model binding.
     *
     * @return self
     */
    protected function createRoute(): self
    {
        $path = base_path('routes/web.php');
        $this->files->append($path, '// TO' . "DO Add to {$this->module} module Route::resource('{$this->singular}', '{$this->singularClass}Controller');\n");
        $this->info('Routes updated successfully');

        $path = app_path('Providers/RouteServiceProvider.php');
        $this->files->append($path, '// TO' . "DO Add to \$modelRouteBindings '{$this->singular}' => \App\Repositories\Contracts\\{$this->singularClass}Repository::class,\n");
        $this->info('Route bindings updated successfully');

        return $this;
    }

    /**
     * Create the controller stub.
     *
     * @return self
     */
    protected function createController(): self
    {
        $path = app_path("Http/Controllers/{$this->moduleClass}/{$this->singularClass}Controller.php");
        $stub = $this->getStub('controller');

        $this->files->put($path, $this->replacePlaceholders($stub));
        $this->info('Controller created successfully');

        return $this;
    }

    /**
     * Create form requests.
     *
     * @return self
     */
    protected function createFormRequests(): self
    {
        $directory = app_path("Http/Requests/{$this->singularClass}");
        $this->files->makeDirectory($directory);

        $stubs = $this->getStubs('requests');
        foreach ($stubs as $name => $stub) {
            $path = $directory . DIRECTORY_SEPARATOR . studly_case($name) . '.php';
            $this->files->put($path, $this->replacePlaceholders($stub));
        }

        $this->info('Form requests created successfully');

        return $this;
    }

    /**
     * Create form requests.
     *
     * @return self
     */
    protected function createResourceViews(): self
    {
        $directory = resource_path("views/modules/{$this->module}/{$this->singular}");
        $this->files->makeDirectory($directory);

        $stubs = $this->getStubs('resource-views');
        foreach ($stubs as $name => $stub) {
            $path = "$directory/$name.php";
            $this->files->put($path, $this->replacePlaceholders($stub));
        }

        $this->info('Resource views created successfully');

        return $this;
    }

    /**
     * Create the submodule navigation.
     *
     * @return self
     */
    protected function createSubmodule(): self
    {
        $path = resource_path("views/modules/{$this->module}.blade.php");
        $stub = $this->getStub('submodule');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Submodule view updated successfully');

        return $this;
    }

    /**
     * Create the feature tests methods for the model.
     *
     * @return self
     */
    protected function createFeatureTest(): self
    {
        $path = base_path("tests/Feature/{$this->moduleClass}ModuleTest.php");
        $stub = $this->getStub('tests/feature/submodule');
        $this->files->append($path, $this->replacePlaceholders($stub));
        $this->info('Submodule feature tests created successfully');

        return $this;
    }

    /**
     * Inflect the placeholder values.
     *
     * @param  string $model
     * @param  string $module
     * @return self
     */
    protected function inflect(string $model, string $module): self
    {
        $this->singular = Str::camel(Str::singular($model));    // fooBar
        $this->singularClass = Str::studly($this->singular);    // FooBar
        $this->plural = Str::plural($this->singular);           // fooBars
        $this->pluralClass = Str::studly($this->plural);        // FooBars

        $this->module = Str::camel($module);                    // master
        $this->moduleClass = Str::studly($this->module);        // Master

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
     * Get the contents of the stubs in a directory.
     *
     * @param  string $directory
     * @return array
     */
    protected function getStubs(string $directory): array
    {
        $path = resource_path("stubs/$directory");
        $files = $this->files->files($path);

        $stubs = [];
        foreach ($files as $file)
            $stubs[$file->getBasename('.stub')] = $this->files->get($file->getPathname());

        return $stubs;
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

        $stub = str_replace(['Bogus', 'bogus'], [$this->moduleClass, $this->module], $stub);
        $stub = str_replace(['Dummies', 'dummies'], [$this->pluralClass, $this->plural], $stub);

        return str_replace(['Dummy', 'dummy'], [$this->singularClass, $this->singular], $stub);
    }
}
