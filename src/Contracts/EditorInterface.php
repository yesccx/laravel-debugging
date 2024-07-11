<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Contracts;

use Yesccx\Debugging\Exceptions\InstallationException;
use Yesccx\Debugging\Foundation\Editor\Installer;

interface EditorInterface
{
    /**
     * @param Installer $installer
     *
     * @return bool
     * @throws InstallationException
     */
    public function install(Installer $installer): bool;

    /**
     * @param Installer $installer
     *
     * @return bool
     * @throws InstallationException
     */
    public function uninstall(Installer $installer): bool;
}
