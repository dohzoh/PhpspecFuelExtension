<?php

namespace PhpSpec\Fuelphp\Generator;

use PhpSpec\CodeGenerator\Generator\SpecificationGenerator;
use PhpSpec\Fuelphp\Locator\PSR0Resource;
use PhpSpec\Locator\ResourceInterface;

class FuelphpSpecificationGenerator extends SpecificationGenerator
{

    /**
     * @param ResourceInterface $resource
     * @param string            $generation
     * @param array             $data
     *
     * @return boolean
     */
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return 'specification' === $generation && $resource instanceof PSR0Resource;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return file_get_contents(__FILE__, null, null, __COMPILER_HALT_OFFSET__);
    }
    /**
     * @param ResourceInterface $resource
     * @param string            $filepath
     *
     * @return string
     */
    protected function renderTemplate(ResourceInterface $resource, $filepath)
    {
        $controller = <<<CONTROLLER
    function let(Request \$request, Response \$response){
        \$this->beConstructedWith(\$request, \$response);
    }

CONTROLLER;

        $values = array(
            '%filepath%'  => $filepath,
            '%name%'      => $resource->getSpecName(),
            '%namespace%' => $resource->getSpecNamespace(),
            '%subject%'   => $resource->getSrcClassname(),
            "%constructor%" => preg_match("!^Controller_!i", $resource->getSpecName())?$controller:"",
        );

        if (!$content = $this->getTemplateRenderer()->render('specification', $values)) {
            $content = $this->getTemplateRenderer()->renderString($this->getTemplate(), $values);
        }

        return $content;
    }

}
__halt_compiler();<?php

use PhpSpec\ObjectBehavior;

class %name% extends ObjectBehavior
{
%constructor%
    function it_is_initializable()
    {
        $this->shouldHaveType('%subject%');
    }
}
