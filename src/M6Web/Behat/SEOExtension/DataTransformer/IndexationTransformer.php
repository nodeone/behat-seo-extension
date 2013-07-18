<?php

namespace M6Web\Behat\SEOExtension\DataTransformer;

/**
 * Class IndexationTransformer transforms the value contained in the meta
 * "robots" tag to the value to set in the step "the page should <>":
 *
 * "index, follow":   be indexable
 * "noindex, follow": not be indexable
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class IndexationTransformer implements TransformerInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return "indexation";
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        $transformedData = "";
        if (0 === strpos($data, 'index')) {
            $transformedData = "be indexable";
        } elseif (!empty($data)) {
            $transformedData = "not be indexable";
        }

        return $transformedData;
    }
}
