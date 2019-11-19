<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsArticleContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\NewsAnnouncementsArticle;
use Oro\Bundle\UserBundle\DataFixtures\UserUtilityTrait;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\LoadWebCatalogDemoData as BaseLoadWebCatalogDemoData;

/**
 * Web catalog demo data
 */
class LoadNewsAnnouncementsDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
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
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/news_announcements_data.yml'
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

        if ($variant->getType() === NewsAnnouncementsArticleContentVariantType::TYPE) {
            $article = new NewsAnnouncementsArticle();
            $article->setDescription($params['description']);
            $article->setShortDescription($params['shortDescription']);

            if (isset($params['image'])) {
//                $article->setImage();
            }

            $variant->setNewsAnnouncementsArticle($article);
        }

        return $variant;
    }
}
