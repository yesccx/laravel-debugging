<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Console;

use Yesccx\Debugging\Foundation\Editor\Installer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;

final class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debugging:install {type=nano : nano, vim, ...}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Debugging software.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        ['type' => $type] = $this->fetchOptions();

        $this->alert('Install the Debugging software.');

        (new Installer($this))->run($type);
    }

    /**
     * Fetch the command options.
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function fetchOptions(): array
    {
        $type = (string) $this->argument('type');

        return [
            'type' => $type,
        ];
    }
}
