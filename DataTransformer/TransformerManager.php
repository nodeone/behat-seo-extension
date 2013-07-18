<?php

namespace SEOChecker\Behat\SEOExtension\DataTransformer;

class TransformerManager
{
    /**
     * @var array
     */
    private $transformers;

    /**
     * @param TransformerInterface $transformer
     */
    public function register(TransformerInterface $transformer)
    {
        $this->transformers[$transformer->getName()] = $transformer;
    }

    /**
     * Return the stack of transformer.
     *
     * @return array
     */
    public function getManagers()
    {
        return $this->transformers;
    }

    /**
     * Transform the data.
     * It iterates over the data and uses the key to find the corresponding transformer.
     *
     * @param  array $data
     * @return array
     */
    public function transform(array $data)
    {
        $transformedData = $data;
        foreach ($data as $name => $value) {
            if (!array_key_exists($name, $this->transformers)) {
                continue;
            }

            $transformer = $this->transformers[$name];
            $transformedData[$name] = $transformer->transform($value);
        }

        return $transformedData;
    }
}
