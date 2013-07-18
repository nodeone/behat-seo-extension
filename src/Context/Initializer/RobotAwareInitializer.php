<?php

namespace SEOChecker\Behat\SEOExtension\Context\Initializer;

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use Roboxt\File;
use SEOChecker\Behat\SEOExtension\Context\RobotAwareInterface;

/**
 * RobotAwareInitializer
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class RobotAwareInitializer implements InitializerInterface
{
    /**
     * @var File
     */
    private $file;

    /**
     * @param string $robotsFilePath
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ContextInterface $context)
    {
        return $context instanceof RobotAwareInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ContextInterface $context)
    {
        $context->setFile($this->file);
    }
}
