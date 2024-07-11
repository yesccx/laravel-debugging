<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Console;

use Illuminate\Console\Command;

final class IndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debugging
        {--F|force : Force debugging tools installation.}
        {--E|editor=nano : nano, vim, ...}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debugging.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        ['force' => $force, 'editor' => $editor] = $this->fetchOptions();

        if ($force || $this->confirm('Confirm install the Debugging software?', false)) {
            $this->call(InstallCommand::class, ['type' => $editor]);
        } else {
            $this->error('Exit!');

            return;
        }

        if ($force || $this->confirm('Confirm disable the PHP Opcache?', true)) {
            $this->call(OpcacheCommand::class, ['action' => 'false']);
        }

        if ($force || $this->confirm('Confirm restart the PHP FPM service?', true)) {
            $this->call(ServiceCommand::class, ['action', 'restart']);
        }

        $this->newLine();
        $this->info('Finished!');
    }

    /**
     * Fetch the command options.
     *
     * @return array
     */
    private function fetchOptions(): array
    {
        $force = (bool) $this->option('force');

        $editor = (string) $this->option('editor');

        return [
            'force'  => $force,
            'editor' => $editor,
        ];
    }
}
