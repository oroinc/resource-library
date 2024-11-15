<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\ResourceLibraryContentVariantType;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

class LoadResourceLibraryTestData extends AbstractLoadWebCatalogTestData implements DependentFixtureInterface
{
    public const RESOURCE_LIBRARY_NODE_REFERENCE_NAME = 'resource_library_node';

    #[\Override]
    public function getDependencies(): array
    {
        return [
            LoadWebCatalogTestData::class
        ];
    }

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $webCatalog = $this->getReference(LoadWebCatalogTestData::WEB_CATALOG_REFERENCE_NAME);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Tests/Functional/DataFixtures/data/resource_library_data.yml'
            ),
            $this->getReference(LoadWebCatalogTestData::WEB_CATALOG_NODE_REFERENCE_NAME)
        );

        $this->generateCache($webCatalog);
    }

    #[\Override]
    protected function getContentVariant(ObjectManager $manager, string $type, array $params): ContentVariant
    {
        $variant = new ContentVariant();
        $variant->setType($type);

        if ($variant->getType() === ResourceLibraryContentVariantType::TYPE) {
            $variant->setDescription($params['description']);
        }

        return $variant;
    }
}
