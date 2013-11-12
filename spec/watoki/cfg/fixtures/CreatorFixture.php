<?php
namespace spec\watoki\cfg\fixtures;
 
use watoki\cfg\Creator;
use watoki\scrut\Fixture;

/**
 * @property FileFixture file <-
 */
class CreatorFixture extends Fixture {

    public function whenICreateAUserConfigurationFor_Into($baseClass, $fileName) {
        $creator = new Creator();
        $creator->createStub($baseClass, $this->file->getFullPath($fileName));
    }
}
 