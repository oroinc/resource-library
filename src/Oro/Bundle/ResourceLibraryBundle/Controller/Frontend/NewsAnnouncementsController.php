<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsArticleContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\NewsAnnouncementsArticle;
use Oro\Bundle\ResourceLibraryBundle\Entity\Repository\NewsAnnouncementsArticleRepository;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Provides actions to display news and announcements
 */
class NewsAnnouncementsController extends AbstractController
{
    /**
     * @Route("/", name="oro_resource_library_news_announcements_index", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function indexAction(ContentVariant $contentVariant = null): array
    {
        if (!$contentVariant || $contentVariant->getType() !== NewsAnnouncementsContentVariantType::TYPE) {
            throw $this->createNotFoundException();
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
                'today' => $this->getArticleRepository()->findTodayArticles(
                    $contentVariant,
                    $this->get(ScopeManager::class)->getCriteria('web_content')
                ),
            ],
        ];
    }

    /**
     * @Route("/article/{id}", name="oro_resource_library_news_announcements_article", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function articleAction(ContentVariant $contentVariant = null): array
    {
        if (!$contentVariant
            || $contentVariant->getType() !== NewsAnnouncementsArticleContentVariantType::TYPE
            || !$contentVariant->getNewsAnnouncementsArticle()
        ) {
            throw $this->createNotFoundException();
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
                'alsoInteresting' => $this->getArticleRepository()->findAlsoInterestingArticles(
                    $contentVariant,
                    $this->get(ScopeManager::class)->getCriteria('web_content')
                ),
            ],
        ];
    }

    /**
     * @return NewsAnnouncementsArticleRepository
     */
    private function getArticleRepository(): NewsAnnouncementsArticleRepository
    {
        return $this->get('doctrine')->getRepository(NewsAnnouncementsArticle::class);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ScopeManager::class,
        ]);
    }
}
