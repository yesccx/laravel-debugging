<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;

final class ServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debugging:service {action=restart : stop|start|restart.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP FPM service management.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert('PHP FPM service management.');

        ['action' => $action] = $this->fetchOptions();

        switch ($action) {
            case 'stop':
                $this->comment('>> supervisorctl stop php-fpm');
                passthru('supervisorctl stop php-fpm');
                break;
            case 'start':
                $this->comment('>> supervisorctl start php-fpm');
                passthru('supervisorctl start php-fpm');
                break;
            case 'restart':
                $this->comment('>> supervisorctl restart php-fpm');
                passthru('supervisorctl restart php-fpm');
                break;
        }

        passthru('supervisorctl status php-fpm');
    }

    /**
     * Fetch the command options.
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function fetchOptions(): array
    {
        $action = (string) $this->argument('action');

        if (!in_array($action, ['stop', 'start', 'restart'])) {
            throw new InvalidArgumentException('The action invalid.');
        }

        return [
            'action' => $action,
        ];
    }
}
