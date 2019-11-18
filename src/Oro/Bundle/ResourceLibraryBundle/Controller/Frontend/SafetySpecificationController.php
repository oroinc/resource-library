<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationPageContentVariantType;
use Oro\Bundle\ScopeBundle\Entity\Scope;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Provides action for Safety Specification pages
 */
class SafetySpecificationController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ContentNodeTreeResolverInterface::class,
            ScopeManager::class
        ]);
    }

    /**
     * @Route("/", name="oro_resource_library_safety_specification_index", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function indexAction(ContentVariant $contentVariant = null): array
    {
        // If ContentVariant has no appropriate type we consider it is not valid
        if (!$contentVariant || $contentVariant->getType() !== SafetySpecificationPageContentVariantType::TYPE) {
            throw $this->createNotFoundException();
        }

        $scope = $this->get(ScopeManager::class)->findOrCreate('web_content');
        if (!$scope instanceof Scope) {
            throw $this->createNotFoundException();
        }

        $contentNode = $contentVariant->getNode();

        $resolvedContentNode = $this->get(ContentNodeTreeResolverInterface::class)
            ->getResolvedContentNode($contentNode, $scope);

        return ['data' => [
            'contentNode' => $resolvedContentNode
        ]];
    }
}
