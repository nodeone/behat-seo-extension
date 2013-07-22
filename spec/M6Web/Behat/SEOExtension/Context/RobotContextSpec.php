<?php

namespace spec\M6Web\Behat\SeoExtension\Context;

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
        File $file
    )
    {
        $session->getCurrentUrl()->willReturn('/foo');
        $mink->getSession()->willReturn($session);
        $this->setMink($mink);

        $file->isUrlAllowedByUserAgent(Argument::type('string'), Argument::any())
            ->shouldBeCalled()
            ->willReturn(true)
        ;
        $this->setFile($file);

        $this->shouldNotThrow('M6Web\Behat\SeoExtension\Exception\NotIndexablePageException')
            ->during('assertUrlIsIndexable', ["/bar"])
        ;
    }
}
