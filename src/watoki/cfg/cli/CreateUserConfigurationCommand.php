<?php
namespace watoki\cfg\cli;

use watoki\cfg\Creator;
use watoki\cli\commands\DefaultCommand;
use watoki\cli\Console;
use watoki\factory\Factory;

class CreateUserConfigurationCommand extends DefaultCommand {

    /** @var string */
    private $baseClass;

    /** @var string */
    private $targetFile;

    /**
     * @param string $baseClass Default configuration class to be overwritten
     * @param string $targetFile File to save user configuration class in
     * @param Factory $factory
     */
    function __construct($baseClass, $targetFile, Factory $factory = null) {
        parent::__construct($factory);

        $this->baseClass = $baseClass;
        $this->targetFile = $targetFile;
    }

    /**
     * Creates a user configuration class to overwrite a default configuration
     */
    public function doExecute(Console $console) {
        if (file_exists($this->targetFile)) {
            $console->out->writeLine('Already exists');
            return;
        }

        $creator = new Creator();
        $creator->createStub($this->baseClass, $this->targetFile);
        $console->out->writeLine('Created user configuration in ' . $this->targetFile);
    }

}
