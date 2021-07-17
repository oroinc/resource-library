<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsArticleContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\NewsAnnouncementsArticle;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;

/**
 * News & Announcements demo data
 */
class LoadNewsAnnouncementsDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
{
    use LoadDemoFileTrait;

    /** @var \DateTime */
    private $createdAt;

    /** @var ObjectManager */
    private $manager;

    /** @var \DirectoryIterator */
    private $images;

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

        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/news_announcements_data.yml'
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
            case NewsAnnouncementsArticleContentVariantType::TYPE:
                $article = new NewsAnnouncementsArticle();
                $article->setDescription($params['description']);
                $article->setShortDescription($params['shortDescription']);

                $this->createdAt->modify('-' . rand(180, 360) . ' minutes');
                $article->setCreatedAt(clone $this->createdAt);
                $article->setImage($this->getNextImage($this->manager));

                $variant->setNewsAnnouncementsArticle($article);
                break;
            case NewsAnnouncementsContentVariantType::TYPE:
                $variant->setDescription($params['description']);
        }

        return $variant;
    }

    private function getNextImage(ObjectManager $manager, bool $useDam = true): File
    {
        if ($this->images === null) {
            $this->images = new \DirectoryIterator(
                $this->getFileLocator()->locate(
                    '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/demo_picts/'
                )
            );

            $this->images->rewind();
        }

        $rewound = false;
        do {
            $this->images->next();
            if (!$this->images->valid()) {
                if ($rewound) {
                    throw new \LogicException(
                        sprintf('Directory "%s" not has readable image files', $this->images->getPath())
                    );
                }

                $this->images->rewind();
                $rewound = true;
            }

            $current = $this->images->current();
        } while (!$current->isFile() || $current->getExtension() !== 'jpg' || !$current->isReadable());

        return $this->createFileFile($manager, $current->getPathname(), \basename($current->getPathname()), $useDam);
    }
}
