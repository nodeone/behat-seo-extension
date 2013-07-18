<?php

namespace SEOChecker\Behat\SEOExtension\EventSubscriber;

use Behat\Behat\Event\OutlineEvent;
use SEOChecker\Behat\SEOExtension\Loader\ExampleLoaderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * OutlineSubscriber
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class OutlineSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var ExampleLoaderInterface
     */
    private $loader;

    /**
     * @var bool
     */
    private $disabled;

    /**
     * @param $file
     * @param ExampleLoaderInterface $loader
     */
    public function __construct($file, ExampleLoaderInterface $loader)
    {
        $this->file = $file;
        $this->loader = $loader;
    }

    /**
     * Disable the listener.
     */
    public function disable()
    {
        $this->disabled = true;
    }

    /**
     * @param OutlineEvent $event
     */
    public function beforeOutline(OutlineEvent $event)
    {
        if ($this->disabled) {
            return;
        }

        $this->loader->load($this->file, $event->getOutline()->getExamples());
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'beforeOutline' => 'beforeOutline',
        ];
    }
}
