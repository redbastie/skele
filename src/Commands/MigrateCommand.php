<?php

namespace Redbastie\Skele\Commands;

use Doctrine\DBAL\Schema\Comparator;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class MigrateCommand extends Command
{
    protected $signature = 'skele:migrate {--fresh} {--seed}';

    public function handle()
    {
        Artisan::call('migrate' . ($this->option('fresh') ? ':fresh' : '') . ' --force');

        $filesystem = new Filesystem;

        if ($filesystem->exists($dir = app_path('Models'))) {
            foreach ($filesystem->allFiles($dir) as $file) {
                $class = app('App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname()));

                if (method_exists($class, 'migration')) {
                    if (Schema::hasTable($class->getTable())) {
                        $tempTable = 'temp_' . $class->getTable();

                        Schema::dropIfExists($tempTable);
                        Schema::create($tempTable, function (Blueprint $table) use ($class) {
                            $class->migration($table);
                        });

                        $schemaManager = $class->getConnection()->getDoctrineSchemaManager();
                        $classTableDetails = $schemaManager->listTableDetails($class->getTable());
                        $tempTableDetails = $schemaManager->listTableDetails($tempTable);
                        $tableDiff = (new Comparator)->diffTable($classTableDetails, $tempTableDetails);

                        if ($tableDiff) {
                            $schemaManager->alterTable($tableDiff);
                        }

                        Schema::drop($tempTable);
                    }
                    else {
                        Schema::create($class->getTable(), function (Blueprint $table) use ($class) {
                            $class->migration($table);
                        });
                    }
                }
            }
        }

        $this->info('Migration complete!');

        if ($this->option('seed')) {
            Artisan::call('db:seed --force');

            $this->info('Seeding complete!');
        }
    }
}
