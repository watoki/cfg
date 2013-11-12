<?php
namespace spec\watoki\cfg;

use spec\watoki\cfg\fixtures\FileFixture;
use spec\watoki\cfg\fixtures\LoaderFixture;
use watoki\scrut\Specification;

/**
 * @property FileFixture file <-
 * @property LoaderFixture loader <-
 */
class LoadUserConfigurationTest extends Specification {

    function testNonExistingFile() {
        $this->loader->whenILoad('MyConfig.php');

        $this->loader->thenAnInstanceOf_ShouldBeTheSingletonOf('name\space\BaseConfiguration', 'name\space\BaseConfiguration');
    }

    function testLoadUserConfiguration() {
        $this->file->givenTheFile_WithContent('ThisConfiguration.php', '<?php namespace name\space; class ThisConfiguration {}');

        $this->loader->whenILoad('ThisConfiguration.php');

        $this->loader->thenAnInstanceOf_ShouldBeTheSingletonOf('name\space\ThisConfiguration', 'name\space\BaseConfiguration');
    }

    function testConstructorParameters() {
        $this->file->givenTheFile_WithContent('ConfigurationWithConstructor.php', '<?php namespace name\space; class ConfigurationWithConstructor {
            function __construct($foo) {
                $this->foo = $foo;
            }
        }');

        $this->loader->whenILoadWithTheConstructorArguments('ConfigurationWithConstructor.php', array('foo' => 'bar'));

        $this->loader->thenAnInstanceOf_ShouldBeTheSingletonOf('name\space\ConfigurationWithConstructor', 'name\space\BaseConfiguration');
        $this->loader->thenTheInstanceShouldHaveTheProperty_WithTheValue('foo', 'bar');
    }

    function testEmptyFile() {
        $this->file->givenTheFile_WithContent('MyConfig.php', '<?php #empty');

        $this->loader->whenITryToLoad('MyConfig.php');

        $this->loader->thenAnExceptionContaining_ShouldBeThrown('Could not find class');
    }

    function testWrongNamespace() {
        $this->file->givenTheFile_WithContent('WrongNamespace.php', '<?php namespace wrong\name\space; class WrongNamespace {}');

        $this->loader->whenITryToLoad('WrongNamespace.php');

        $this->loader->thenAnExceptionContaining_ShouldBeThrown('Could not find class');
    }

}