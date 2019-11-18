<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use ArrayIterator;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionItemContentVariantType;
use Oro\Bundle\ScopeBundle\Entity\Scope;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Cache\ResolvedData\ResolvedContentNode;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Videos controller.
 */
class VideosController extends AbstractController
{
    /**
     * @Route("/", name="oro_resource_library_videos_list", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentNode $contentNode
     * @return array
     */
    public function listAction(ContentNode $contentNode = null): array
    {
        if (!$contentNode) {
            throw $this->createNotFoundException();
        }

        $scope = $this->get(ScopeManager::class)->findOrCreate('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $contentNode = $this->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentNode, $scope);

        if (!$contentNode ||
            $contentNode->getResolvedContentVariant()->getType() !== VideoListContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        /** @var ResolvedContentNode $section */
        foreach ($contentNode->getChildNodes() as $section) {
            $this->sortChildNodesByVideoDate($section);
        }

        return [
            'data' => [
                'contentNode' => $contentNode,
            ]
        ];
    }

    /**
     * @param ResolvedContentNode $parentNode
     */
    private function sortChildNodesByVideoDate(ResolvedContentNode &$parentNode): void
    {
        $childNodes = $parentNode->getChildNodes();

        /** @var ArrayIterator $iterator */
        $iterator = $childNodes->getIterator();
        $iterator->uasort(function ($videoNodeA, $videoNodeB) {
            if ($videoNodeA->getResolvedContentVariant()->getType() !== VideoListSectionItemContentVariantType::TYPE) {
                return -1;
            }

            return (
                $videoNodeA->getResolvedContentVariant()->video->getCreatedAt()->getTimestamp() <
                $videoNodeB->getResolvedContentVariant()->video->getCreatedAt()->getTimestamp() ? 1 : -1
            );
        });

        $videoNodes = new ArrayCollection(iterator_to_array($iterator));
        $parentNode->setChildNodes($videoNodes);
    }

    /**
     * @Route("/section", name="oro_resource_library_videos_section", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentNode $contentNode
     * @return array
     */
    public function sectionAction(ContentNode $contentNode = null): array
    {
        if (!$contentNode) {
            throw $this->createNotFoundException();
        }

        $scope = $this->get(ScopeManager::class)->findOrCreate('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $contentNode = $this->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentNode, $scope);

        if (!$contentNode ||
            $contentNode->getResolvedContentVariant()->getType() !== VideoListSectionContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        return [
            'data' => [
                'contentNode' => $contentNode,
            ]
        ];
    }

    /**
     * @Route("/item", name="oro_resource_library_videos_item", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentNode $contentNode
     * @return array
     */
    public function itemAction(ContentNode $contentNode = null): array
    {
        if (!$contentNode) {
            throw $this->createNotFoundException();
        }

        $scope = $this->get(ScopeManager::class)->findOrCreate('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $itemContentNode = $this->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentNode, $scope);

        if (!$itemContentNode ||
            $itemContentNode->getResolvedContentVariant()->getType() !== VideoListSectionItemContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        $parentContentNode = $this->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentNode->getParentNode(), $scope);

        $this->sortChildNodesByVideoDate($parentContentNode);

        if (!$parentContentNode ||
            $parentContentNode->getResolvedContentVariant()->getType() !== VideoListSectionContentVariantType::TYPE
        ) {
            throw $this->createNotFoundException();
        }

        return [
            'data' => [
                'contentNode' => $itemContentNode,
                'parentContentNode' => $parentContentNode,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices()
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
