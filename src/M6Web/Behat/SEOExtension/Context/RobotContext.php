<?php

namespace M6Web\Behat\SEOExtension\Context;

use Behat\Behat\Context\Step;
use Behat\MinkExtension\Context\RawMinkContext;
use Roboxt\File;
use M6Web\Behat\SEOExtension\Context\RobotAwareInterface;
use M6Web\Behat\SEOExtension\Exception\NotIndexablePageException;

/**
 * RobotContext
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class RobotContext extends RawMinkContext implements RobotAwareInterface
{
    /**
     * @var string
     */
    private $robotsFilePath;

    /**
     * @var File
     */
    private $file;

    /**
     * The User-Agent parameter
     *
     * @var string
     */
    private $userAgent;

    /**
     * @param string $robotsFilePath
     */
    public function setRobotsFilePath($robotsFilePath)
    {
        $this->robotsFilePath = $robotsFilePath;
    }

    /**
     * @param File $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * @Given /^A bot goes on "(?P<url>[^"]*)" with user-agent "(?P<userAgent>[^"]*)"$/
     * @Given /^A bot "(?P<userAgent>[^"]*)" goes on "(?P<url>[^"]*)"$/
     */
    public function visitWithUserAgent($url, $userAgent)
    {
        $this->userAgent = $userAgent;

        $this->getSession()->setRequestHeader('User-Agent', $this->userAgent);
        $this->getSession()->visit($this->locatePath($url));
    }

    /**
     * @Then /^the robots file should exist$/
     */
    public function assertRobotsFileExists()
    {
        return array(
            new Step\Given("I am on \"/robots.txt\""),
            new Step\Then("the response status code should be 200"),
        );
    }

    /**
     * @Then /^the robots file's content should be valid$/
     */
    public function assertOnlineRobotsFileEqualsBaseRobotsFile()
    {
        return new Step\Then("the response should contain \"{$this->file->getContent()}\"");
    }

    /**
     * @Then /^robots should be able to index the page$/
     */
    public function assertUrlIsIndexable()
    {
        $session = $this->getMink()->getSession();
        $url = $session->getCurrentUrl();

        if (!$this->file->isUrlAllowedByUserAgent($url, $this->userAgent)) {
            throw new NotIndexablePageException(sprintf('The page "%s" should not be indexable according to the robots.txt file.', $url));
        }
    }

    /**
     * @Then /^robots should not be able to index the page$/
     */
    public function assertUrlIsNotIndexable()
    {
        $url = $this->getMink()->getSession()->getCurrentUrl();

        if ($this->file->isUrlAllowedByUserAgent($url, $this->userAgent)) {
            throw new NotIndexablePageException(sprintf('The page "%s" should be indexable according to the robots.txt file.', $url));
        }
    }
}
