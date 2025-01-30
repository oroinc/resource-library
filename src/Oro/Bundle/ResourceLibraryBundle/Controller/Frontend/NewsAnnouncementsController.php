<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\LayoutBundle\Attribute\Layout;
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
    #[Route(path: '/', name: 'oro_resource_library_news_announcements_index', requirements: ['id' => '\d+'])]
    #[Layout]
    public function indexAction(?ContentVariant $contentVariant = null): array
    {
        if (!$contentVariant || $contentVariant->getType() !== NewsAnnouncementsContentVariantType::TYPE) {
            throw $this->createNotFoundException();
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
                'today' => $this->getArticleRepository()->findTodayArticles(
                    $contentVariant,
                    $this->container->get(ScopeManager::class)->getCriteria('web_content')
                ),
            ],
        ];
    }

    #[Route(
        path: '/article/{id}',
        name: 'oro_resource_library_news_announcements_article',
        requirements: ['id' => '\d+']
    )]
    #[Layout]
    public function articleAction(?ContentVariant $contentVariant = null): array
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
                    $this->container->get(ScopeManager::class)->getCriteria('web_content')
                ),
            ],
        ];
    }

    private function getArticleRepository(): NewsAnnouncementsArticleRepository
    {
        return $this->container->get('doctrine')->getRepository(NewsAnnouncementsArticle::class);
    }

    #[\Override]
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ScopeManager::class,
            'doctrine' => ManagerRegistry::class,
        ]);
    }
}
