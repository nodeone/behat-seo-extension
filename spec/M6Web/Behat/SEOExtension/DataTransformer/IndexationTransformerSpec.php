<?php

namespace spec\M6Web\Behat\SEOExtension\DataTransformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexationTransformerSpec extends ObjectBehavior
{
    function it_should_transform_indexation_values()
    {
        $this->transform("index, follow")->shouldReturn('be indexable');
        $this->transform("noindex, follow")->shouldReturn('not be indexable');
    }
}
