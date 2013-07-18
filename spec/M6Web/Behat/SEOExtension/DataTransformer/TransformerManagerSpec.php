<?php

namespace spec\M6Web\Behat\SEOExtension\DataTransformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use M6Web\Behat\SEOExtension\DataTransformer\TransformerInterface;

class TransformerManagerSpec extends ObjectBehavior
{
    private $transformer;

    function let(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
        $this->transformer->getName()->shouldBeCalled()->willReturn('foo');

        $this->register($this->transformer);
    }

    function it_should_transform_data()
    {
        $data = ['foo' => 'bar'];

        $this->transformer->transform($data['foo'])->shouldBeCalled()->will(
            function () use ($data) { return strtoupper($data['foo']); }
        );

        $this->transform($data)->shouldReturn(['foo' => 'BAR']);
    }

    function it_should_not_transform_data()
    {
        $data = ['bar' => 'foo'];

        $this->transformer->transform(Argument::any())->shouldNotBeCalled();

        $this->transform($data)->shouldReturn($data);
    }
}
