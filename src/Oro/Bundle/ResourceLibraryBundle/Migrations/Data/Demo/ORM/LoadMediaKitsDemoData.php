<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListItemContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\MediaKit;
use Oro\Bundle\UserBundle\DataFixtures\UserUtilityTrait;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\LoadWebCatalogDemoData as BaseLoadWebCatalogDemoData;

/**
 * Media Kits demo data
 */
class LoadMediaKitsDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
{
    use UserUtilityTrait;

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            BaseLoadWebCatalogDemoData::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $webCatalog = $this->getWebCatalog($manager);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/media_kits_data.yml'
            ),
            $this->getRootNode($manager)
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
     * @param ObjectManager $manager
     * @return ContentNode
     */
    private function getRootNode(ObjectManager $manager)
    {
        return $manager->getRepository(ContentNode::class)
            ->findOneBy(['parentNode' => null]);
    }

    /**
     * @param string $type
     * @param array $params
     * @return ContentVariant
     */
    protected function getContentVariant($type, array $params)
    {
        $variant = new ContentVariant();
        $variant->setType($type);

        if ($type === MediaKitListContentVariantType::TYPE) {
            $variant->setDescription($params['description']);
        } elseif ($type === MediaKitListItemContentVariantType::TYPE) {
            $mediaKit = new MediaKit();

            $variant->setMediaKit($mediaKit);
        }

        return $variant;
    }
}
