<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\ResourceLibraryContentVariantType;
use Oro\Bundle\UserBundle\DataFixtures\UserUtilityTrait;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\LoadWebCatalogDemoData as BaseLoadWebCatalogDemoData;

/**
 * Resource Library demo data
 */
class ResourceLibraryDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
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
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/resource_library_data.yml'
            ),
            $this->getRootNode($manager)
        );

        $manager->flush();

        $this->generateCache($webCatalog);
    }

    /**
     * @param ObjectManager $manager
     * @return ContentNode
     */
    public static function getResourceLibraryNode(ObjectManager $manager): ContentNode
    {
        return $manager->getRepository(ContentNode::class)
            ->createQueryBuilder('node')
            ->innerJoin('node.titles', 't')
            ->where('t.string = :title')
            ->setParameter(':title', 'Resource Library')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
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
     * {@inheritdoc}
     */
    protected function getContentVariant($type, array $params)
    {
        $variant = new ContentVariant();
        $variant->setType($type);

        switch ($variant->getType()) {
            case ResourceLibraryContentVariantType::TYPE:
                $variant->setDescription($params['description']);
        }

        return $variant;
    }
}
