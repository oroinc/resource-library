<?php

namespace Oro\Bundle\ResourceLibraryBundle\Model;

use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\ResourceLibraryBundle\Entity\MediaKit;

/**
 * Makes MediaKit entity extendable.
 *
 * @method null|File getBanner()
 * @method MediaKit setBanner(File $image)
 * @method null|File getMediaKitFile()
 * @method MediaKit setMediaKitFile(File $mediaKitFile)
 * @method null|File getLogoPackageFile()
 * @method MediaKit setLogoPackageFile(File $logoPackageFile)
 */
class ExtendMediaKit
{
    /**
     * Constructor
     *
     * The real implementation of this method is auto generated.
     *
     * IMPORTANT: If the derived class has own constructor it must call parent constructor.
     */
    public function __construct()
    {
    }
}
