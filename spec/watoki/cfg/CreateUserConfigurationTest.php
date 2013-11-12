<?php
namespace spec\watoki\cfg;
use spec\watoki\cfg\fixtures\CreatorFixture;
use spec\watoki\cfg\fixtures\FileFixture;
use watoki\scrut\Specification;

/**
 * @property FileFixture file <-
 * @property CreatorFixture creator <-
 */
class CreateUserConfigurationTest extends Specification {

    function testCreateConfigurationStub() {
        $this->file->givenTheClass_InTheNamespace('BaseConfiguration', 'some\name\space');

        $this->creator->whenICreateAUserConfigurationFor_Into('some\name\space\BaseConfiguration', 'UserConfiguration.php');

        $this->file->thenTheContentOf_ShouldBe('UserConfiguration.php', '<?php
namespace some\name\space;

class UserConfiguration extends BaseConfiguration {

}');
    }

}
 