<?php

namespace M6Web\Behat\SEOExtension\Loader;

use Behat\Gherkin\Node\TableNode;
use M6Web\Behat\SEOExtension\DataTransformer\TransformerManager;
use M6Web\Behat\SEOExtension\Exception\FileNotFoundException;

class CSVExampleLoader implements ExampleLoaderInterface
{

    const URL = "url";
    const USERAGENT = "user-agent";
    const ROBOTS = "indexation";
    const CANONICAL = "canonical";
    const HANDHELD = "alternate handheld";
    const ONLYSCREEN640 = "alternate screen smaller than 640px";
    const TITLE = "title";
    const DESCRIPTION = "description";

    /**
     * @var TransformerManager
     */
    private $transformer;

    /**
     * @param TransformerManager $transformer
     */
    public function __construct(TransformerManager $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param $file
     * @param  TableNode $table
     * @return mixed
     */
    public function load($file, TableNode $table)
    {
        $values = $this->read($file);

        // The first line of the rows should be the columns' name.
        $keys = array_keys(reset($values));
        array_unshift($values, $keys);

        $table->setRows($values);
    }

    /**
     * Parse a CSV file and construct a ruleset.
     *
     * @param $file
     * @return array
     * @throws FileNotFoundException
     */
    private function read($file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException();
        }

        $values = [];
        $columns = [];
            // We loop on every line
        foreach (file($file) as $line => $data) {

            $data = str_getcsv (html_entity_decode(utf8_encode(trim($data))), ";", '"');

		    if ($line == 0) {
                $columns = $this->getColumns($data);

                continue;
            }

            // We fill a new array with empty fields with the same number of elements we have in columns array
            $dataTemp = array_fill(0, count($columns), null);

            //lets add the two arrays
            $data = $data+$dataTemp;

            // and remove the potential empty extra fields
            $data = array_slice($data, 0, count($columns));

            $values[] = $this->transformer->transform(array_combine($columns, $data));
        }

        return $values;
    }

    /**
     * @param  array $names
     * @return array
     */
    private function getColumns(array $names)
    {
        return array_map(function ($column) {
            return constant(__CLASS__.'::'.$column);
        }, $names);
    }
}
