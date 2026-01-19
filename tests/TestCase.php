<?php

namespace Venturecraft\Revisionable\Tests;

use DB;
use Illuminate\Support\Facades\Schema;
use Venturecraft\Revisionable\Tests\Models\User;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'testbench']);

        // call migrations specific to our tests, e.g. to seed the db
        // the path option should be an absolute path.
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => realpath(__DIR__.'/../src/migrations'),
        ]);

        // Bind mock RevisionRepository Class
        $this->app->singleton(\App\Repositories\Revision\RevisionRepository::class, function () { 
            return new class {
                public function getExtraAttributes(): array { 
                    return []; 
                } 
            }; 
        });
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
    }
}
