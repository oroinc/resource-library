<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionItemContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\Video;
use Oro\Bundle\UserBundle\DataFixtures\UserUtilityTrait;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;

/**
 * Videos demo data
 */
class LoadVideosDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
{
    use UserUtilityTrait;

    #[\Override]
    public function getDependencies()
    {
        return [
            ResourceLibraryDemoData::class,
        ];
    }

    #[\Override]
    public function load(ObjectManager $manager)
    {
        $webCatalog = $this->getWebCatalog($manager);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/videos_data.yml'
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

    #[\Override]
    protected function getContentVariant($type, array $params)
    {
        $variant = new ContentVariant();
        $variant->setType($type);

        switch ($variant->getType()) {
            case VideoListContentVariantType::TYPE:
                $variant->setDescription($params['description']);
                break;

            case VideoListSectionItemContentVariantType::TYPE:
                $video = new Video();
                $video->setShortDescription($params['shortDescription']);
                $video->setDescription($params['description']);
                $video->setLink($params['link']);

                $variant->setVideo($video);
                break;
        }

        return $variant;
    }
}
