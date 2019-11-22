<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\DigitalAssetBundle\Entity\DigitalAsset;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Bundle\UserBundle\DataFixtures\UserUtilityTrait;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\File\File as ComponentFile;

/**
 * Provides methods for creating file entities on loading sample data
 */
trait LoadDemoFileTrait
{
    use UserUtilityTrait;

    /**
     * @return FileLocator
     */
    private function getFileLocator(): FileLocator
    {
        if (property_exists($this, 'container') && $this->container instanceof ContainerInterface) {
            return $this->container->get('file_locator');
        }

        throw new \LogicException(sprintf('For use %s declare method %s', self::class, __METHOD__));
    }

    /**
     * @param ObjectManager $manager
     * @param string $pathname
     * @param string $title
     * @param bool $useDam
     * @return File
     */
    private function createFileFile(ObjectManager $manager, string $pathname, string $title, bool $useDam = true): File
    {
        $file = new File();
        $file->setFile(new ComponentFile($pathname));

        if ($useDam) {
            $localizedFallbackValue = new LocalizedFallbackValue();
            $localizedFallbackValue->setString($title);
            $manager->persist($localizedFallbackValue);

            $user = $this->getFirstUser($manager);
            $digitalAsset = new DigitalAsset();
            $digitalAsset->addTitle($localizedFallbackValue)
                ->setSourceFile($file)
                ->setOwner($user)
                ->setOrganization($user->getOrganization());
            $manager->persist($digitalAsset);
            $manager->persist($file);

            $file = clone $file;
            $file->setDigitalAsset($digitalAsset);
        }

        return $file;
    }
}
