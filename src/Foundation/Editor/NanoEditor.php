<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Foundation\Editor;

use Yesccx\Debugging\Contracts\EditorInterface;
use Yesccx\Debugging\Exceptions\InstallationException;

class NanoEditor implements EditorInterface
{
    public const PACKAGE_FILE = 'nano_6.2-1_amd64.deb';

    /**
     * @param Installer $installer
     *
     * @return bool
     * @throws InstallationException
     */
    public function install(Installer $installer): bool
    {
        if ($this->checkExists()) {
            $installer->getOutput()->warn('The editor is already installed.');

            return false;
        }

        $filepath = './vendor/yesccx/laravel-debugging/libs/' . self::PACKAGE_FILE;

        if (!file_exists($filepath)) {
            throw new InstallationException('Installation package not found.');
        }

        tap(
            sprintf('dpkg -i %s', $filepath),
            function($command) use ($installer) {
                $installer->getOutput()->comment(">> {$command}");
                passthru($command);
            }
        );

        if (!$this->checkExists()) {
            throw new InstallationException('Installation failed!');
        }

        return true;
    }

    /**
     * @param Installer $installer
     *
     * @return bool
     * @throws InstallationException
     */
    public function uninstall(Installer $installer): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function checkExists(): bool
    {
        return !empty(trim(exec('which nano')));
    }
}
