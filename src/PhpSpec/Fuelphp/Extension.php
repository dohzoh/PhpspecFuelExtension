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
echo __METHOD__."()\n";
        self::loadPhpunitConfiguration($container);

        $container->addConfigurator(function (ServiceContainer $c) {
            $suites = $c->getParam('modules', array('main' => ''));

            foreach ($suites as $name => $suite) {
//print_r($suite);
                $suite      = is_array($suite) ? $suite : array('namespace' => $suite);
                $srcNS      = isset($suite['namespace']) ? $suite['namespace'] : '';
                $specPrefix = isset($suite['spec_prefix']) ? $suite['spec_prefix'] : 'spec';
                $srcPath    = isset($suite['src_path']) ? $suite['src_path'] : 'src';
                $specPath   = isset($suite['spec_path']) ? $suite['spec_path'] : '.';

                if (!is_dir($srcPath)) {
                    mkdir($srcPath, 0777, true);
                }
                if (!is_dir($specPath)) {
                    mkdir($specPath, 0777, true);
                }

                $c->set(sprintf('locator.locators.%s', $name),
                    function () use ($srcNS, $specPrefix, $srcPath, $specPath) {
                        return new PSR0Locator($srcNS, null, $srcPath . strtolower($srcNS). '/classes/', $specPath . '/spec/');
                    }
                );
            }
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
