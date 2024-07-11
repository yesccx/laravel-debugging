<?php

declare(strict_types = 1);

namespace Yesccx\Debugging\Foundation\Editor;

use Yesccx\Debugging\Contracts\EditorInterface;
use Yesccx\Debugging\Exceptions\InstallationException;
use Illuminate\Console\Command as Output;

final class Installer
{
    /**
     * @var array
     */
    protected static $editors = [];

    /**
     * @param Output $output
     */
    public function __construct(
        protected Output $output,
    ) {
    }

    /**
     * Register the editor.
     *
     * @param array|string $type
     * @param null|string $editorClass
     *
     * @return void
     */
    public static function registerEditor(array|string $type, ?string $editorClass = null): void
    {
        if (is_array($type)) {
            foreach ($type as $key => $value) {
                static::registerEditor($key, $value);
            }
        } else {
            static::$editors[$type] = $editorClass;
        }
    }

    /**
     * @param string $type
     *
     * @return void
     */
    public function run(string $type): void
    {
        try {
            $editor = $this->makeEditor($type);

            $this->output->info(sprintf('Resolved editor instance %s.', get_class($editor)));

            $this->output->info('Start installation...');

            $result = $editor->install($this);
        } catch (InstallationException $e) {
            $this->output->error($e->getMessage());

            return;
        }

        if ($result) {
            $this->output->info('Installation is completed!');
        }
    }

    /**
     * @param string $type
     *
     * @return EditorInterface
     * @throws InstallationException
     */
    protected function makeEditor(string $type): EditorInterface
    {
        if (!isset(static::$editors[$type])) {
            throw new InstallationException("Editor [{$type}] is not supported.");
        }

        return new static::$editors[$type];
    }

    /**
     * @return Output
     */
    public function getOutput(): Output
    {
        return $this->output;
    }
}
