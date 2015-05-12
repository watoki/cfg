<?php
namespace spec\watoki\cfg\fixtures;
 
use watoki\factory\Factory;
use watoki\scrut\Fixture;
use watoki\scrut\Specification;

class FileFixture extends Fixture {

    private $dir;

    /**
     * @param Specification $spec
     * @param Factory $factory <-
     */
    public function __construct(Specification $spec, Factory $factory) {
        parent::__construct($spec, $factory);
        $this->dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid() . DIRECTORY_SEPARATOR;

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
        $fullFileName = $this->dir . $fileName;
        if (!file_exists(dirname($fullFileName))) {
            mkdir(dirname($fullFileName), 0777, true);
        }
        file_put_contents($fullFileName, $content);
    }

    public function getFullPath($fileName) {
        return $this->dir . $fileName;
    }

    public function thenTheContentOf_ShouldBe($fileName, $content) {
        $this->spec->assertEquals($content, file_get_contents($this->getFullPath($fileName)));
    }

}
 