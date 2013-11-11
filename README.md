# cfg [![Build Status](https://travis-ci.org/watoki/cfg.png?branch=master)](https://travis-ci.org/watoki/cfg)

*cfg* is a simple configuration loader.

## Usage ##

Given you have a configuration class `name\space\MyAppConfiguration` then you can overwrite it with a
deployment-specific configuration `name\space\MyConfiguration` in a file `some/folder/MyConfiguration.php` and load it with

    $loader = new Loader(new Factory());
    $config = $loader->loadConfiguration('name\space\MyAppConfiguration', 'some/folder/MyConfiguration.php');

This will result in `MyConfiguration` being set as singleton for `MyAppConfiguration`, so whenever the latter one is
injected through the Factory, the former is used instead.

Note that `MyConfiguration` needs to be in the same namespace as `MyAppConfiguration` and the name of the containing file matches
the class name.

## Installation ##

To use cfg in your own project with [Composer], add the following lines to your `composer.json`.

    "require" : {
        "watoki/cfg" : "*"
    }

[Composer]: http://getcomposer.org/
