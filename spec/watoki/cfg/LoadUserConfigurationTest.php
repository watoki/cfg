<?php
namespace spec\watoki\cfg;

use watoki\cfg\Loader;
use watoki\factory\Factory;
use watoki\scrut\Specification;

class LoadUserConfigurationTest extends Specification {

    function testNonExistingFile() {
        $this->whenILoad('MyConfig.php');

        $this->thenAnInstanceOf_ShouldBeTheSingletonOf('name\space\BaseConfiguration', 'name\space\BaseConfiguration');
    }

    function testLoadUserConfiguration() {
        $this->givenTheFile_WithContent('ThisConfiguration.php', '<?php namespace name\space; class ThisConfiguration {}');

        $this->whenILoad('ThisConfiguration.php');

        $this->thenAnInstanceOf_ShouldBeTheSingletonOf('name\space\ThisConfiguration', 'name\space\BaseConfiguration');
    }

    function testConstructorParameters() {
        $this->givenTheFile_WithContent('ConfigurationWithConstructor.php', '<?php namespace name\space; class ConfigurationWithConstructor {
            function __construct($foo) {
                $this->foo = $foo;
            }
        }');

        $this->whenILoadWithTheConstructorArguments('ConfigurationWithConstructor.php', array('foo' => 'bar'));

        $this->thenAnInstanceOf_ShouldBeTheSingletonOf('name\space\ConfigurationWithConstructor', 'name\space\BaseConfiguration');
        $this->thenTheInstanceShouldHaveTheProperty_WithTheValue('foo', 'bar');
    }

    function testEmptyFile() {
        $this->givenTheFile_WithContent('MyConfig.php', '<?php #empty');

        $this->whenITryToLoad('MyConfig.php');

        $this->thenAnExceptionContaining_ShouldBeThrown('Could not find class');
    }

    function testWrongNamespace() {
        $this->givenTheFile_WithContent('WrongNamespace.php', '<?php namespace wrong\name\space; class WrongNamespace {}');

        $this->whenITryToLoad('WrongNamespace.php');

        $this->thenAnExceptionContaining_ShouldBeThrown('Could not find class');
    }

    ####################################### SET-UP ##################################################

    /** @var Factory */
    private $myFactory;

    /** @var null|\Exception */
    private $exception;

    private $dir;

    private $instance;

    protected function background() {
        $this->dir = __DIR__ . '/__tmp/';

        if (!class_exists('name\space\BaseConfiguration')) {
            eval ('namespace name\space; class BaseConfiguration {}');
        }

        $dir = $this->dir;
        $cleanUp = function () use ($dir) {
            foreach (glob($dir . '/*') as $file) {
                @unlink($file);
            }
            @rmdir($dir);
        };

        $this->undos[] = function () use ($cleanUp) {
            $cleanUp();
        };

        $cleanUp();
        @mkdir($this->dir);
    }

    private function givenTheFile_WithContent($fileName, $content) {
        file_put_contents($this->dir . $fileName, $content);
    }

    private function thenAnExceptionContaining_ShouldBeThrown($text) {
        $this->assertNotNull($this->exception, 'Should have thrown exception');
        $this->assertContains($text, $this->exception->getMessage());
    }

    private function whenITryToLoad($fileName) {
        try {
            $this->whenILoad($fileName);
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }

    private function whenILoad($fileName) {
        $this->whenILoadWithTheConstructorArguments($fileName, array());
    }

    private function whenILoadWithTheConstructorArguments($fileName, $args) {
        $this->myFactory = new Factory();
        $loader = new Loader($this->myFactory);
        $loader->loadConfiguration('name\space\BaseConfiguration', $this->dir . $fileName, $args);
    }

    private function thenAnInstanceOf_ShouldBeTheSingletonOf($userClass, $baseClass) {
        $this->instance = $this->myFactory->getSingleton($baseClass);
        $this->assertInstanceOf($userClass, $this->instance);
    }

    private function thenTheInstanceShouldHaveTheProperty_WithTheValue($name, $value) {
        $this->assertEquals($value, $this->instance->$name);
    }

}