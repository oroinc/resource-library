<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListItemContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\MediaKit;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

class LoadMediaKitTestData extends AbstractLoadWebCatalogTestData implements DependentFixtureInterface
{
    use LoadTestFileTrait;

    private ObjectManager $manager;

    #[\Override]
    public function getDependencies()
    {
        return [
            LoadResourceLibraryTestData::class,
        ];
    }

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $webCatalog = $this->getReference(LoadWebCatalogTestData::WEB_CATALOG_REFERENCE_NAME);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Tests/Functional/DataFixtures/data/media_kits_data.yml'
            ),
            $this->getReference(LoadResourceLibraryTestData::RESOURCE_LIBRARY_NODE_REFERENCE_NAME)
        );

        $this->generateCache($webCatalog);
    }

    #[\Override]
    protected function getContentVariant($type, array $params): ContentVariant
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
