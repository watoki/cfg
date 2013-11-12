<?php
namespace watoki\cfg;
 
class Creator {

    public function createStub($baseClass, $targetFile) {
        $userClass = substr(basename($targetFile), 0, -4);

        $reflection = new \ReflectionClass($baseClass);
        $namespace = $reflection->getNamespaceName();
        $baseClassName = $reflection->getShortName();

        file_put_contents($targetFile, "<?php
namespace $namespace;

class $userClass extends $baseClassName {

}");
    }
}
 