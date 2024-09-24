<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\LayoutBundle\Attribute\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\ResourceLibraryContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\NewsAnnouncementsArticle;
use Oro\Bundle\ResourceLibraryBundle\Entity\Repository\NewsAnnouncementsArticleRepository;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Cache\ResolvedData\ResolvedContentNode;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Provides action for resource library page.
 */
class ResourceLibraryController extends AbstractController
{
    use ContentNodeAwareControllerTrait;

    #[Route(path: '/', name: 'oro_resource_library_index', requirements: ['id' => '\d+'])]
    #[Layout]
    public function listAction(ContentVariant $contentVariant = null): array
    {
        $resolvedContentNode = $this->resolveTree($contentVariant, ResourceLibraryContentVariantType::TYPE);

        /** @var ResolvedContentNode $node */
        foreach ($resolvedContentNode->getChildNodes() as $node) {
            if ($node->getResolvedContentVariant()->getType() === NewsAnnouncementsContentVariantType::TYPE) {
                $latestNews = $this->getArticleRepository()->findLatest(
                    $node->getId(),
                    $this->container->get(ScopeManager::class)->getCriteria('web_content')
                );

                break;
            }
        }

        return [
            'data' => [
                'contentNode' => $contentVariant ? $contentVariant->getNode() : null,
                'resolvedContentNode' => $resolvedContentNode,
                'latestNews' => $latestNews ?? new ArrayCollection(),
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
        return \array_merge(parent::getSubscribedServices(), [
            ContentNodeTreeResolverInterface::class,
            ScopeManager::class,
            'doctrine' => ManagerRegistry::class,
        ]);
    }
}
