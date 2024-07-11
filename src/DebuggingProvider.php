<?php

declare(strict_types = 1);

namespace Yesccx\Debugging;

use Yesccx\Debugging\Console\IndexCommand;
use Yesccx\Debugging\Console\InstallCommand;
use Yesccx\Debugging\Console\OpcacheCommand;
use Yesccx\Debugging\Console\ServiceCommand;
use Yesccx\Debugging\Foundation\Editor\Installer;
use Yesccx\Debugging\Foundation\Editor\NanoEditor;
use Yesccx\Debugging\Foundation\Editor\VimEditor;
use Illuminate\Support\ServiceProvider;

class DebuggingProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();

        Installer::registerEditor([
            'vim'  => VimEditor::class,
            'nano' => NanoEditor::class,
        ]);
    }

    /**
     * Register the package commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            IndexCommand::class,
            InstallCommand::class,
            OpcacheCommand::class,
            ServiceCommand::class,
        ]);
    }
}
