<?php

namespace M6Web\Behat\SEOExtension\Loader;

use Behat\Gherkin\Node\TableNode;
use M6Web\Behat\SEOExtension\DataTransformer\TransformerManager;
use M6Web\Behat\SEOExtension\Exception\FileNotFoundException;

class CSVExampleLoader implements ExampleLoaderInterface
{
    const SEPARATOR = ";";

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
        // If we open the file correctly
        if (false !== ($handle = fopen($file, "r"))) {
            $firstLine = true;
            $columns = [];
            // We loop on every line
            while (false !== ($data = fgetcsv($handle, 0, self::SEPARATOR))) {
                if ($firstLine) {
                    $columns = $this->getColumns($data);
                    $firstLine = false;

                    continue;
                }

                $values[] = $this->transformer->transform(array_combine($columns, $data));
            }
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
