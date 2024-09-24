<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationPageContentVariantType;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

class LoadSafetySpecificationsTestData extends AbstractLoadWebCatalogTestData implements DependentFixtureInterface
{
    #[\Override]
    public function getDependencies(): array
    {
        return [
            LoadResourceLibraryTestData::class,
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
                '@OroResourceLibraryBundle/Tests/Functional/DataFixtures/data/safety_specification_data.yml'
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

        if ($variant->getType() === SafetySpecificationPageContentVariantType::TYPE) {
            $variant->setDescription($params['description']);
        }

        return $variant;
    }
}
