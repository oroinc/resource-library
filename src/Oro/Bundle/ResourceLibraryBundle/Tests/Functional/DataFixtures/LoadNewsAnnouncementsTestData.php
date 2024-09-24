<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use DateTime;
use DateTimeZone;
use DirectoryIterator;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use LogicException;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsArticleContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\NewsAnnouncementsArticle;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

class LoadNewsAnnouncementsTestData extends AbstractLoadWebCatalogTestData implements DependentFixtureInterface
{
    use LoadTestFileTrait;

    private DateTime $createdAt;
    private ObjectManager $manager;
    private DirectoryIterator $images;

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
        $this->manager = $manager;
        $webCatalog = $this->getReference(LoadWebCatalogTestData::WEB_CATALOG_REFERENCE_NAME);

        $this->createdAt = new DateTime('now', new DateTimeZone('UTC'));
        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Tests/Functional/DataFixtures/data/news_announcements_data.yml'
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
        if (!isset($this->images)) {
            $this->images = new DirectoryIterator(
                $this->getFileLocator()->locate(
                    '@OroResourceLibraryBundle/Tests/Functional/DataFixtures/data/demo_picts/'
                )
            );

            $this->images->rewind();
        }

        $rewound = false;
        do {
            $this->images->next();
            if (!$this->images->valid()) {
                if ($rewound) {
                    throw new LogicException(
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
