<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationFileContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationPageContentVariantType;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;

/**
 * Safety Specifications demo data
 */
class LoadSafetySpecificationsDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
{
    use LoadDemoFileTrait;

    /** @var ObjectManager */
    private $manager;

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
        $this->manager = $manager;
        $webCatalog = $this->getWebCatalog($manager);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/safety_specification_data.yml'
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
            case SafetySpecificationPageContentVariantType::TYPE:
                $variant->setDescription($params['description']);
                break;

            case SafetySpecificationFileContentVariantType::TYPE:
                static $file;
                if (!$file) {
                    $file = $this->createFileFile(
                        $this->manager,
                        $this->getFileLocator()->locate(
                            '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/dummy.pdf'
                        ),
                        'dummy'
                    );
                }

                $variant->setDescription($params['description']);
                $variant->setPdfFile(clone $file);
                break;
        }

        return $variant;
    }
}
