<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Foundation\Editor;

use Yesccx\Debugging\Contracts\EditorInterface;
use Yesccx\Debugging\Exceptions\InstallationException;
use Yesccx\Debugging\Foundation\Editor\Installer;

class VimEditor implements EditorInterface
{
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

        tap(
            'apt-get update && apt-get install vim -y',
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
        return !empty(trim(exec('which vim')));
    }
}
