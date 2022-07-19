<?php

namespace Sparors\Ussd\Commands;

use Illuminate\Support\Facades\File;

/** @since v2.5.0 */
class ConfiguratorCommand extends GenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:configurator {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new ussd machine configurator';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $namespace = config('ussd.configurator_namespace', 'App\Http\Ussd\Configurator');
        $name = $this->argument('name');

        if (! File::exists($this->pathFromNamespace($namespace, $name))) {
            $content = preg_replace_array(
                ['/\[namespace\]/', '/\[class\]/'],
                [$this->classNamespace($namespace, $name), $this->className($name)],
                file_get_contents(__DIR__.'/configurator.stub')
            );

            $this->ensureDirectoryExists($namespace, $name);
            File::put($this->pathFromNamespace($namespace, $name), $content);

            $this->info($this->className($name).' configurator created successfully');
        } else {
            $this->error('File already exists !');
        }
    }
}
