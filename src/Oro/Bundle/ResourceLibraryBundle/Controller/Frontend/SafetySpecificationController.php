<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationPageContentVariantType;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Provides action for Safety Specification pages
 */
class SafetySpecificationController extends AbstractController
{
    use ContentNodeAwareControllerTrait;

    /**
     * @Route("/", name="oro_resource_library_safety_specification_index", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function indexAction(ContentVariant $contentVariant = null): array
    {
        return [
            'data' => [
                'resolvedContentNode' => $this->resolveTree(
                    $contentVariant,
                    SafetySpecificationPageContentVariantType::TYPE
                ),
                'contentNode' => $contentVariant ? $contentVariant->getNode() : null
            ]
        ];
    }
}
