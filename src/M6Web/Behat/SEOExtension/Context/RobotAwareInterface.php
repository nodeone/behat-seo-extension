<?php

namespace M6Web\Behat\SEOExtension\Context;

use Roboxt\File;

/**
 * RobotAwareInterface
 *
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
interface RobotAwareInterface
{
    /**
     * Set the File used by the context.
     *
     * @param File $file
     */
    public function setFile(File $file);
}
