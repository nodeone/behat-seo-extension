<?php

namespace M6Web\Behat\SEOExtension\Loader;

use Behat\Gherkin\Node\TableNode;

/**
 * ExampleLoaderInterface
 *
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
interface ExampleLoaderInterface
{
    /**
     * @param $file
     * @param  TableNode $table
     * @return mixed
     */
    public function load($file, TableNode $table);
}
