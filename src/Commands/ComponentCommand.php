<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Console\Command;
use Livewire\Commands\ComponentParser;

class ComponentCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'skele:component {class} {--full} {--modal}';

    public function handle()
    {
        $componentParser = new ComponentParser('App\\Components', resource_path('views'), $this->argument('class'));

        if ($this->option('full')) {
            $stubFolder = 'component-full';
        }
        else if ($this->option('modal')) {
            $stubFolder = 'component-modal';
        }
        else {
            $stubFolder = 'component';
        }

        $this->createFiles($stubFolder, [
            'app/Components/DummyComponent.php.stub' => $componentParser->relativeClassPath(),
            'resources/views/DummyView.blade.php.stub' => $componentParser->relativeViewPath(),
            'DummyComponentNamespace' => $componentParser->classNamespace(),
            'DummyComponent' => $componentParser->className(),
            'DummyRouteUri' => $dummyRouteUri = str_replace('.', '/', $componentParser->viewName()),
            'DummyViewName' => $componentParser->viewName(),
            'DummyViewTitle' => preg_replace('/(.)(?=[A-Z])/u', '$1 ', $componentParser->className()),
            'DummyWisdom' => $componentParser->wisdomOfTheTao(),
        ]);

        $this->warn('<info>' . $this->argument('class') . '</info> component & view generated! ' .
            ($this->option('full') ? '<href=' . url($dummyRouteUri) . '>' . url($dummyRouteUri) . '</>' : ''));
    }
}
