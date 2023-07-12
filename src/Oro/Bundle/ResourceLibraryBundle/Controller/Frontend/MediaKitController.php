<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListContentVariantType;
use Oro\Bundle\ScopeBundle\Entity\Scope;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * MediaKits controller.
 */
class MediaKitController extends AbstractController
{
    /**
     * @Route("/", name="oro_resource_library_media_kit_list", requirements={"id"="\d+"})
     * @Layout()
     */
    public function listAction(ContentVariant $contentVariant = null): array
    {
        if (!$contentVariant) {
            throw $this->createNotFoundException();
        }

        $scope = $this->get(ScopeManager::class)->findMostSuitable('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $resolvedContentNode = $this->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentVariant->getNode(), $scope);

        if (!$resolvedContentNode ||
            $resolvedContentNode->getResolvedContentVariant()->getType() !== MediaKitListContentVariantType::TYPE
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
