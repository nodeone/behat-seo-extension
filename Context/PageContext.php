<?php

namespace SEOChecker\Behat\SEOExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\Step;

/**
 * PageContext
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class PageContext extends RawMinkContext
{
    /**
     * @Then /^the page should be indexable$/
     */
    public function thePageShouldBeIndexable()
    {
        return array(
            new Step\Then("the \"content\" attribute of \"meta[name='robots']\" element should contain \"index\""),
            new Step\Then("the \"content\" attribute of \"meta[name='robots']\" element should not contain \"noindex\""),
            new Step\Then("robots should be able to index the page")
        );
    }

    /**
     * @Then /^the page should not be indexable$/
     */
    public function thePageShouldNotBeIndexable()
    {
        return array(
            new Step\Then("the \"content\" attribute of \"meta[name='robots']\" element should contain \"noindex\""),
            new Step\Then("robots should not be able to index the page")
        );
    }

    /**
     * @Then /^the canonical url should be "(?P<url>[^"]*)"$/
     */
    public function thePageShouldHaveAValidCanonicalLink($url)
    {
        if (empty($url)) {
            return;
        }

        return array(
            new Step\Then("the \"href\" attribute of \"link[rel='canonical']\" element should contain \"$url\""),
            new Step\Then("page \"$url\" should exist"),
            new Step\Then("I move backward one page"), // the previous step goes on the url but doesn't come back...
        );
    }

    /**
     * @Then /^the alternate for media "(?P<media>[^"]+)" should be "(?P<url>[^"]*)"$/
     */
    public function thePageShouldHaveAValidAlternateLink($media, $url)
    {
        if (empty($url)) {
            return;
        }

        return array(
            new Step\Then("the \"href\" attribute of \"link[rel='alternate'][media='$media']\" element should contain \"$url\""),
            new Step\Then("page \"$url\" should exist"),
            new Step\Then("I move backward one page"),
        );
    }

    /**
     * @Then /^the title of the page should be$/
     * @Then /^the title of the page should be "(?P<title>[^"]*)"$/
     */
    public function assertPageTitle($title)
    {
        if ($title instanceof PyStringNode) {
            $title = $title->getRaw();
        }

        if (empty($title)) {
            return;
        }

        return array(
            new Step\Then("the \"title\" element should contain \"$title\"")
        );
    }

    /**
     * @Then /^the description of the page should be$/
     * @Then /^the description of the page should be "(?P<description>[^"]*)"$/
     */
    public function assertPageDescription($description)
    {
        if ($description instanceof PyStringNode) {
            $description = $description->getRaw();
        }

        if (empty($description)) {
            return;
        }

        return array(
            new Step\Then("the \"content\" attribute of \"meta[name='description']\" element should contain \"$description\""),
        );
    }

    /**
     * Checks, that an attribute of an element with specified CSS contains specific value.
     *
     * @Then /^the "(?P<attribute>[^"]*)" attribute of "(?P<element>[^"]*)" element should contain "(?P<value>(?:[^"]|\\")*)"$/
     *
     * This should be removed when the Pull Request https://github.com/Behat/MinkExtension/pull/93 will be merged.
     */
    public function assertElementAttributeContains($element, $attribute, $value)
    {
        $selectorType = 'css';
        $selector = $element;
        $element = $this->elementAttributeExists($selectorType, $selector, $attribute);
        $actual  = $element->getAttribute($attribute);
        $regex   = '/'.preg_quote($value, '/').'/ui';

        if (!preg_match($regex, $actual)) {
            $message = sprintf('The text "%s" was not found in the attribute "%s" of the element matching %s "%s".', $value, $attribute, $selectorType, $selector);
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * Checks, that an attribute of an element with specified CSS contains specific value.
     *
     * @Then /^the "(?P<attribute>[^"]*)" attribute of "(?P<element>[^"]*)" element should not contain "(?P<value>(?:[^"]|\\")*)"$/
     *
     * This should be removed when the Pull Request https://github.com/Behat/MinkExtension/pull/93 will be merged.
     */
    public function assertElementAttributeNotContains($element, $attribute, $value)
    {
        $selectorType = 'css';
        $selector = $element;
        $element = $this->elementAttributeExists($selectorType, $selector, $attribute);
        $actual  = $element->getAttribute($attribute);
        $regex   = '/'.preg_quote($value, '/').'/ui';

        if (preg_match($regex, $actual)) {
            $message = sprintf('The text "%s" was found in the attribute "%s" of the element matching %s "%s".', $value, $attribute, $selectorType, $selector);
            throw new ExpectationException($message, $this->getSession());
        }
    }


    /**
     * Checks that an attribute exists in an element.
     *
     * This should be removed when the Pull Request https://github.com/Behat/MinkExtension/pull/93 will be merged.
     *
     * @param $selectorType
     * @param $selector
     * @param $attribute
     * @return \Behat\Mink\Element\NodeElement
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function elementAttributeExists($selectorType, $selector, $attribute)
    {
        $element = $this->assertSession()->elementExists($selectorType, $selector);

        if (!$element->hasAttribute($attribute)) {
            $message = sprintf('The attribute "%s" was not found in the element matching %s "%s".', $attribute, $selectorType, $selector);
            throw new ExpectationException($message, $this->getSession());
        }

        return $element;
    }
}
