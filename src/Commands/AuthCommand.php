<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Console\Command;

class AuthCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'skele:auth';

    public function handle()
    {
        $this->createFiles('auth');

        $this->info('Auth generated! <href=' . url('register') . '>' . url('register') . '</>');
    }
}
