PhpspecFuelExtension
==============

Extension for using PhpSpec with FuelPHP framework

**Installation**

```bash
php composer.phar config bin-dir "bin/"
php composer.phar require dohzoh/phpspec-fuel dev-master
```

**Configuration**

```yml
#phpspec.yml
extensions:
    - PhpSpec\Fuelphp\Extension
```

**Usage**

```
bin/phpspec describe Controller_Index
```

```
bin/phpspec run
```
OR
```
bin/phpspec run -b public/index.php
```

You specify the applicaiton root in phpspec.yml, for example:
```
src_path: fuel/app
spec_path: fuel/app/tests
```
This will usually match the path you set for your composer autoload to search for classes.

For documentation on how to use phpspec, see: http://www.phpspec.net/
