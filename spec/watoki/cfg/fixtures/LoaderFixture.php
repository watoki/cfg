<?php
namespace spec\watoki\cfg\fixtures;
 
use watoki\cfg\Loader;
use watoki\factory\Factory;
use watoki\scrut\Fixture;

/**
 * @property FileFixture file <-
 */
class LoaderFixture extends Fixture {

    /** @var Factory */
    private $myFactory;

    /** @var null|\Exception */
    private $exception;

    private $instance;

    public function whenITryToLoad($fileName) {
        try {
            $this->whenILoad($fileName);
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }

    public function whenILoad($fileName) {
        $this->whenILoadWithTheConstructorArguments($fileName, array());
    }

    public function whenILoadWithTheConstructorArguments($fileName, $args) {
        $this->myFactory = new Factory();
        $loader = new Loader($this->myFactory);
        $loader->loadConfiguration('name\space\BaseConfiguration', $this->file->getFullPath($fileName), $args);
    }

    public function thenAnInstanceOf_ShouldBeTheSingletonOf($userClass, $baseClass) {
        $this->instance = $this->myFactory->getSingleton($baseClass);
        $this->spec->assertInstanceOf($userClass, $this->instance);
    }

    public function thenTheInstanceShouldHaveTheProperty_WithTheValue($name, $value) {
        $this->spec->assertEquals($value, $this->instance->$name);
    }

    public function thenAnExceptionContaining_ShouldBeThrown($text) {
        $this->spec->assertNotNull($this->exception, 'Should have thrown exception');
        $this->spec->assertContains($text, $this->exception->getMessage());
    }

}
 