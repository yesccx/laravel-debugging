<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;

final class OpcacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debugging:opcache {action=false : disable|enable or false|true.}
        {--R|restart : Restart the PHP FPM service, Effective immediately.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP Opcache management.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert('PHP Opcache management.');

        ['action' => $action, 'restart' => $restart] = $this->fetchOptions();

        if (empty($configPath = $this->resolveConfigPath())) {
            $this->error('The PHP Opcache config file does not exist.');

            return;
        }

        $this->comment(sprintf('Config path: %s', $configPath));

        $this->switchOpcacheStatus($configPath, $action);

        if ($restart) {
            $this->call(ServiceCommand::class, ['--restart' => true]);
        }
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

        $restart = (bool) $this->option('restart');

        if (!in_array($action, ['enable', 'disable', 'false', 'true'])) {
            throw new InvalidArgumentException('The action invalid.');
        }

        $action = match ($action) {
            'disable', 'false' => false,
            'enable', 'true' => true,
            default => (bool) $action
        };

        return [
            'action' => $action,
            'restart'   => $restart,
        ];
    }

    /**
     * Resolve the PHP Opcache config file path.
     *
     * @return string
     */
    protected function resolveConfigPath(): string
    {
        $filepath = '/etc/php/8.1/fpm/conf.d/98-opcache.ini';

        return $filepath;
    }

    /**
     * Switch the PHP Opcache status.
     *
     * @param string $filepath
     * @param bool $status
     *
     * @return void
     * @throws \Exception
     */
    protected function switchOpcacheStatus(string $filepath, bool $status = false): void
    {
        $this->newLine();

        if (!file_exists($filepath)) {
            throw new \Exception('The opcache config file does not exist.');
        }

        $content = preg_replace(
            '/opcache\.enable\=[01]/',
            sprintf('opcache.enable=%s', $status ? '1' : '0'),
            file_get_contents($filepath)
        );

        file_put_contents($filepath, $content);

        passthru(sprintf('cat %s', $filepath));

        $this->newLine();
        $this->info(sprintf('The PHP Opcache has been %s!', $status ? 'enabled' : 'disabled'));
        $this->newLine();
    }
}
