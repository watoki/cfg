<?php
namespace watoki\cfg;

use watoki\factory\Factory;

class Loader {

    private $factory;

    /**
     * @param Factory $factory <-
     */
    function __construct(Factory $factory) {
        $this->factory = $factory;
    }

    public function loadConfiguration($baseClass, $file, $constructorArgs = array()) {
        if (!file_exists($file)) {
            return $this->factory->setSingleton($baseClass, $this->factory->getInstance($baseClass, $constructorArgs));
        }

        /** @noinspection PhpIncludeInspection */
        require_once($file);

        $reflection = new \ReflectionClass($baseClass);
        $namespace = $reflection->getNamespaceName();

        $userClass = $namespace . '\\' . substr(basename($file), 0, -4);

        if (!class_exists($userClass)) {
            throw new \InvalidArgumentException("Could not find class [$userClass] in file [$file].");
        }

        return $this->factory->setSingleton($baseClass, $this->factory->getInstance($userClass, $constructorArgs));
    }
}