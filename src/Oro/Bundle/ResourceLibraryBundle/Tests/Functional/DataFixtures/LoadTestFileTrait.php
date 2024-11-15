<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\DigitalAssetBundle\Entity\DigitalAsset;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Bundle\UserBundle\Entity\User;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Provides methods for creating file entities on loading sample data
 */
trait LoadTestFileTrait
{
    private function getFileLocator(): FileLocator
    {
        if (property_exists($this, 'container') && $this->container instanceof ContainerInterface) {
            return $this->container->get('file_locator');
        }

        throw new \LogicException(sprintf('For use %s declare method %s', self::class, __METHOD__));
    }

    private function createFileFile(
        ObjectManager $manager,
        User $owner,
        string $pathname,
        string $title,
        bool $useDam = true
    ): File {
        $fileManager = $this->container->get('oro_attachment.file_manager');
        $file = $fileManager->createFileEntity($pathname);
        $file->setOwner($owner);
        $manager->persist($file);

        if ($useDam) {
            $localizedFallbackValue = new LocalizedFallbackValue();
            $localizedFallbackValue->setString($title);
            $manager->persist($localizedFallbackValue);

            $digitalAsset = new DigitalAsset();
            $digitalAsset->addTitle($localizedFallbackValue)
                ->setSourceFile($file)
                ->setOwner($owner)
                ->setOrganization($owner->getOrganization());
            $manager->persist($digitalAsset);
            $manager->persist($file);

            $file = clone $file;
            $file->setDigitalAsset($digitalAsset);
        }

        return $file;
    }
}
