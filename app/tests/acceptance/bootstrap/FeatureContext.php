<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        doSomethingWith($argument);
//    }
//

    /**
     * @Given /^I click the "([^"]*)" button$/
     */
    public function iClickTheButton($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $el = $page->findById('submitAnnouncement');

        $el->click();

    }

    /**
     * @Given /^I enter in "([^"]*)" with "([^"]*)"$/
     */
    public function iEnterInWith($arg1, $arg2)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $field = $page->findField($arg1);

        if(null === $field){
            throw new Exception(
                "$arg1 field not found!"
            );
        }

        $field->setValue($arg2);
    }

    /**
     * @Given /^I forceCheck "([^"]*)"$/
     */
    public function iForceCheck($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $field = $page->findField($arg1);

        if(null === $field){
            throw new Exception(
                "$arg1 field not found!"
            );
        }

        $field->check();
    }

    /**
     * @Given /^I forceClick the "([^"]*)" button$/
     */
    public function iForceclickTheButton($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $button = $page->findButton($arg1);

        if(null === $button){
            throw new Exception(
                "$arg1 button not found!"
            );
        }

        $button->press();

    }
}
