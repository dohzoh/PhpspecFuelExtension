#language: en
Feature: Developer generates a spec
  As a Developer
  I want to automate creating specs
  In order to avoid repetitive tasks and interruptions in development flow

  Scenario: Generating a spec without configuration
    When I start describing the "CodeGeneration/SpecExample1/Markdown" class
    Then a new spec should be generated in the "spec/CodeGeneration/SpecExample1/MarkdownSpec.php":
      """
      <?php

      namespace spec\CodeGeneration\SpecExample1;

      use PhpSpec\ObjectBehavior;
      use Prophecy\Argument;

      class MarkdownSpec extends ObjectBehavior
      {
          function it_is_initializable()
          {
              $this->shouldHaveType('CodeGeneration\SpecExample1\Markdown');
          }
      }

      """

  Scenario: Generating a spec for a class with psr4 prefix
    Given the config file contains:
      """
      suites:
        behat_suite:
          namespace: Behat\CodeGeneration
          psr4_prefix: Behat\CodeGeneration
      """
    When I start describing the "Behat/CodeGeneration/Markdown" class
    Then a new spec should be generated in the "spec/MarkdownSpec.php":
      """
      <?php

      namespace spec\Behat\CodeGeneration;

      use PhpSpec\ObjectBehavior;
      use Prophecy\Argument;

      class MarkdownSpec extends ObjectBehavior
      {
          function it_is_initializable()
          {
              $this->shouldHaveType('Behat\CodeGeneration\Markdown');
          }
      }

      """

  Scenario: Generating a spec with configuration
    Given the config file contains:
      """
      src_path: fuel/app
      spec_path: fuel/app/tests
      phpunit.xml: fuel/core/phpunit.xml

      extensions:
        - PhpSpec\Fuelphp\Extension

      """
    When I start describing the "markdown" class
    Then a new spec should be generated in the "fuel/app/tests/spec/markdownSpec.php":
      """
      <?php

      use PhpSpec\ObjectBehavior;

      class markdownSpec extends ObjectBehavior
      {

          function it_is_initializable()
          {
              $this->shouldHaveType('markdown');
          }
      }

      """

