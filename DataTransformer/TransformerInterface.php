<?php

namespace SEOChecker\Behat\SEOExtension\DataTransformer;

/**
 * TransformerInterface
 *
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
interface TransformerInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * Transforms the given data and returns it.
     *
     * @param $data
     * @return mixed
     */
    public function transform($data);
}
