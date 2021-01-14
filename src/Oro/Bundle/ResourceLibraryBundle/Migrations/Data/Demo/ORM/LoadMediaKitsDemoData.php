<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListItemContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\MediaKit;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;

/**
 * Media Kits demo data
 */
class LoadMediaKitsDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
{
    use LoadDemoFileTrait;

    /** @var ObjectManager */
    private $manager;

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            ResourceLibraryDemoData::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $webCatalog = $this->getWebCatalog($manager);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/media_kits_data.yml'
            ),
            ResourceLibraryDemoData::getResourceLibraryNode($manager)
        );

        $manager->flush();

        $this->generateCache($webCatalog);
    }

    /**
     * @param ObjectManager $manager
     * @return WebCatalog
     */
    private function getWebCatalog(ObjectManager $manager)
    {
        return $manager->getRepository(WebCatalog::class)
            ->findOneBy(['name' => self::DEFAULT_WEB_CATALOG_NAME]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getContentVariant($type, array $params)
    {
        $variant = new ContentVariant();
        $variant->setType($type);

        switch ($variant->getType()) {
            case MediaKitListContentVariantType::TYPE:
                $variant->setDescription($params['description']);
                break;
            case MediaKitListItemContentVariantType::TYPE:
                $mediaKit = new MediaKit();
                $mediaKit->setDescription($params['description']);
                $mediaKit->setLink($params['link']);
                $mediaKitFile =  $this->createFileFile(
                    $this->manager,
                    $this->getFileLocator()->locate($params['banner']),
                    $params['bannerTitle']
                );
                $mediaKit->setBanner($mediaKitFile);
                $mediaKit->setLogoPackageFile($mediaKitFile);
                $mediaKit->setMediaKitFile($mediaKitFile);

                $variant->setMediaKit($mediaKit);
                break;
        }

        return $variant;
    }
}
