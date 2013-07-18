<?php

namespace spec\M6Web\Behat\SEOExtension\Loader;

use Behat\Gherkin\Node\TableNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use M6Web\Behat\SEOExtension\DataTransformer\TransformerManager;

class CSVExampleLoaderSpec extends ObjectBehavior
{
    private $manager;

    function let(TransformerManager $manager)
    {
        $this->manager = $manager;
        $this->beConstructedWith($this->manager);
    }

    function it_should_load_data_and_add_example_to_a_table_node(TableNode $examples)
    {
        $this->manager->transform(Argument::type('array'))->shouldBeCalled()->willReturnArgument();

        $this->load(__DIR__.'/../../../../fixtures/rules.csv', $examples);
    }

    function it_should_throw_an_exception_if_the_file_is_not_found(TableNode $examples)
    {
        $this->shouldThrow('M6Web\Behat\SEOExtension\Exception\FileNotFoundException')->during('load', [__DIR__.'/fixtures/rules.csv', $examples]);
    }
}
