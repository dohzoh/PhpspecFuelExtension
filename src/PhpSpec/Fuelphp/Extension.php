<?php

namespace PhpSpec\Fuelphp;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\Fuelphp\Generator\FuelphpCodeGenerator;
use PhpSpec\Fuelphp\Generator\FuelphpGenerator;
use PhpSpec\Fuelphp\Generator\FuelphpSpecificationGenerator;
use PhpSpec\Fuelphp\Locator\PSR0Locator;
use PhpSpec\ServiceContainer;
use PHPUnit_Util_Configuration;

class Extension implements ExtensionInterface
{
    const DEFAULTPATH_SRC = "fuel/app";
    const DEFAULTPATH_SPEC = "fuel/app/tests";
    const DEFAULTPATH_PHPUNITXML = "fuel/core/phpunit.xml";
    /**
     *
     * @param \PhpSpec\ServiceContainer $container
     * @return type
     */
    public function load(ServiceContainer $container)
    {
        self::loadPhpunitConfiguration($container);

        $container->addConfigurator(function($c) {
            $c->setShared(
                'locator.locators.kohana_locator',
                function($c) {
                    $srcPath = $c->getParam('src_path', self::DEFAULTPATH_SRC);
                    $specPath = $c->getParam('spec_path', self::DEFAULTPATH_SPEC);
                    return new PSR0Locator(null, null, $srcPath . '/classes/', $specPath . '/spec/');
                }
            );
        });

        $container->setShared('code_generator.generators.kohana_class', function ($c) {
            return new FuelphpCodeGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('code_generator.generators.kohana_specification', function ($c) {
            return new FuelphpSpecificationGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });
    }

    /**
     * set global fuel setting
     *  $_SERVER[
     *   [doc_root] => ../../
     *   [app_path] => fuel/app
     *   [core_path] => fuel/core
     *   [package_path] => fuel/packages
     *   [vendor_path] => fuel/vendor
     *   [FUEL_ENV] => test
     *  ]
     * and
     * load bootstrap;
     * @param ServiceContainer $container
     */
    protected function loadPhpunitConfiguration(ServiceContainer $container)
    {
        try{
            $file = $container->getParam('phpunit.xml', self::DEFAULTPATH_PHPUNITXML);
            $config = PHPUnit_Util_Configuration::getInstance($file);
            $config->handlePHPConfiguration();
            $configures = $config->getPHPUnitConfiguration();
            if(file_exists($configures['bootstrap']))
                include_once($configures['bootstrap']);
        }catch (\Exception $e){
            echo "can't find phpunit.xml\n";
        }
    }
}
