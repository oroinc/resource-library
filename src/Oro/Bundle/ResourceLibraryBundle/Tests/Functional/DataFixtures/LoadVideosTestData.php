<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionItemContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\Video;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

class LoadVideosTestData extends AbstractLoadWebCatalogTestData implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            LoadResourceLibraryTestData::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $webCatalog = $this->getReference(LoadWebCatalogTestData::WEB_CATALOG_REFERENCE_NAME);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Tests/Functional/DataFixtures/data/videos_data.yml'
            ),
            $this->getReference(LoadResourceLibraryTestData::RESOURCE_LIBRARY_NODE_REFERENCE_NAME)
        );

        $this->generateCache($webCatalog);
    }

    protected function getContentVariant($type, array $params): ContentVariant
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
