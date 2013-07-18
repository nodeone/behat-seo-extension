<?php

namespace spec\SeoChecker\Behat\SeoExtension\Context;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Roboxt\File;
use Roboxt\UserAgent;

class RobotContextSpec extends ObjectBehavior
{
    function it_should_assert_that_the_file_allows_bots_to_index_url(
        Mink $mink,
        Session $session,
        File $file,
        UserAgent $userAgent
    )
    {
        $session->getCurrentUrl()->willReturn('/foo');
        $mink->getSession()->willReturn($session);
        $this->setMink($mink);

        $userAgent->isIndexable(Argument::type('string'))->willReturn(true);
        $file->getUserAgent(Argument::any())->willReturn($userAgent);
        $this->setFile($file);

        $this->shouldNotThrow('SeoChecker\Behat\SeoExtension\Exception\NotIndexablePageException')->during('assertUrlIsIndexable', ["/bar"]);
    }
}
