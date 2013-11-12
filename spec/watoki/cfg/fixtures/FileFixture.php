<?php
namespace spec\watoki\cfg\fixtures;
 
use watoki\factory\Factory;
use watoki\scrut\Fixture;
use watoki\scrut\Specification;

class FileFixture extends Fixture {

    private $dir;

    public function __construct(Specification $spec, Factory $factory) {
        parent::__construct($spec, $factory);
        $this->dir = __DIR__ . '/__tmp/';

        $dir = $this->dir;
        $cleanUp = function () use ($dir) {
            foreach (glob($dir . '/*') as $file) {
                @unlink($file);
            }
            @rmdir($dir);
        };

        $this->spec->undos[] = function () use ($cleanUp) {
            $cleanUp();
        };

        $cleanUp();
        @mkdir($this->dir);
    }

    public function givenTheClass_InTheNamespace($class, $namespace) {
        if (!class_exists($namespace . '\\' . $class)) {
            eval ("namespace $namespace; class $class {}");
        }
    }

    public function givenTheFile_WithContent($fileName, $content) {
        file_put_contents($this->dir . $fileName, $content);
    }

    public function getFullPath($fileName) {
        return $this->dir . $fileName;
    }

    public function thenTheContentOf_ShouldBe($fileName, $content) {
        $this->spec->assertEquals($content, file_get_contents($this->getFullPath($fileName)));
    }

}
 