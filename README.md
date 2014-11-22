PhpspecFuelExtension
==============

Extension for using PhpSpec with FuelPHP framework

**Installation**

Download fuelphp framework from http://fuelphp.com/downloads

```bash
wget -O fuelphp.zip http://fuelphp.com/files/download/28
unzip fuelphp.zip
cd fuelphp-1.7.2
```

And install to use composer

```bash
php composer.phar selfupdate
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
phpunit.xml: fuel/core/phpunit.xml
```
This will usually match the path you set for your composer autoload to search for classes.

For documentation on how to use phpspec, see: http://www.phpspec.net/
