<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\DigitalAssetBundle\Entity\DigitalAsset;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Bundle\UserBundle\DataFixtures\UserUtilityTrait;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Provides methods for creating file entities on loading sample data
 */
trait LoadDemoFileTrait
{
    use UserUtilityTrait;

    private function getFileLocator(): FileLocator
    {
        if (property_exists($this, 'container') && $this->container instanceof ContainerInterface) {
            return $this->container->get('file_locator');
        }

        throw new \LogicException(sprintf('For use %s declare method %s', self::class, __METHOD__));
    }

    private function createFileFile(ObjectManager $manager, string $pathname, string $title, bool $useDam = true): File
    {
        $user = $this->getFirstUser($manager);

        $fileManager = $this->container->get('oro_attachment.file_manager');
        $file = $fileManager->createFileEntity($pathname);
        $file->setOwner($user);
        $manager->persist($file);

        if ($useDam) {
            $localizedFallbackValue = new LocalizedFallbackValue();
            $localizedFallbackValue->setString($title);
            $manager->persist($localizedFallbackValue);

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
