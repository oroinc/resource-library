<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use ArrayIterator;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\LayoutBundle\Attribute\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionItemContentVariantType;
use Oro\Bundle\ScopeBundle\Entity\Scope;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Cache\ResolvedData\ResolvedContentNode;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Videos controller.
 */
class VideosController extends AbstractController
{
    #[Route(path: '/', name: 'oro_resource_library_videos_list', requirements: ['id' => '\d+'])]
    #[Layout]
    public function listAction(?ContentVariant $contentVariant = null): array
    {
        if (!$contentVariant) {
            throw $this->createNotFoundException();
        }

        $scope = $this->container->get(ScopeManager::class)->findOrCreate('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $resolvedContentNode = $this->container->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentVariant->getNode(), $scope);

        if (!$resolvedContentNode ||
            $resolvedContentNode->getResolvedContentVariant()->getType() !== VideoListContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        /** @var ResolvedContentNode $section */
        foreach ($resolvedContentNode->getChildNodes() as $section) {
            $this->sortChildNodesByVideoDate($section);
        }

        return [
            'data' => [
                'resolvedContentNode' => $resolvedContentNode,
                'contentVariant' => $contentVariant,
            ]
        ];
    }

    private function sortChildNodesByVideoDate(ResolvedContentNode $parentNode): void
    {
        $childNodes = $parentNode->getChildNodes();

        /** @var ArrayIterator $iterator */
        $iterator = $childNodes->getIterator();
        $iterator->uasort(function ($videoNodeA, $videoNodeB) {
            if ($videoNodeA->getResolvedContentVariant()->getType() !== VideoListSectionItemContentVariantType::TYPE) {
                return -1;
            }

            return (
                $videoNodeB->getResolvedContentVariant()->video->getCreatedAt()->getTimestamp() <=>
                $videoNodeA->getResolvedContentVariant()->video->getCreatedAt()->getTimestamp()
            );
        });

        $videoNodes = new ArrayCollection(iterator_to_array($iterator));
        $parentNode->setChildNodes($videoNodes);
    }

    #[Route(path: '/section', name: 'oro_resource_library_videos_section', requirements: ['id' => '\d+'])]
    #[Layout]
    public function sectionAction(?ContentVariant $contentVariant = null): array
    {
        if (!$contentVariant) {
            throw $this->createNotFoundException();
        }

        $scope = $this->container->get(ScopeManager::class)->find('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $resolvedContentNode = $this->container->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentVariant->getNode(), $scope);

        if (!$resolvedContentNode ||
            $resolvedContentNode->getResolvedContentVariant()->getType() !== VideoListSectionContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        return [
            'data' => [
                'resolvedContentNode' => $resolvedContentNode,
                'contentVariant' => $contentVariant,
            ]
        ];
    }

    #[Route(path: '/item', name: 'oro_resource_library_videos_item', requirements: ['id' => '\d+'])]
    #[Layout]
    public function itemAction(?ContentVariant $contentVariant = null): array
    {
        if (!$contentVariant) {
            throw $this->createNotFoundException();
        }

        $scope = $this->container->get(ScopeManager::class)->find('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $itemContentNode = $this->container->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentVariant->getNode(), $scope);

        if (!$itemContentNode ||
            $itemContentNode->getResolvedContentVariant()->getType() !== VideoListSectionItemContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        $parentContentNode = $this->container->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentVariant->getNode()->getParentNode(), $scope);

        $this->sortChildNodesByVideoDate($parentContentNode);

        if (!$parentContentNode ||
            $parentContentNode->getResolvedContentVariant()->getType() !== VideoListSectionContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
                'resolvedContentNode' => $itemContentNode,
                'resolvedParentContentNode' => $parentContentNode,
            ]
        ];
    }

    #[\Override]
    public static function getSubscribedServices(): array
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                ScopeManager::class,
                ContentNodeTreeResolverInterface::class,
            ]
        );
    }
}
