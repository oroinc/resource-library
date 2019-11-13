<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionItemContentVariantType;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Videos controller.
 */
class VideosController extends AbstractController
{
    /**
     * @Route("/{id}", name="oro_resource_library_videos_list", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function listAction(ContentVariant $contentVariant): array
    {
        if ($contentVariant->getType() !== VideoListContentVariantType::TYPE) {
            throw new NotFoundHttpException();
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
            ]
        ];
    }

    /**
     * @Route("/section/{id}", name="oro_resource_library_videos_section", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function sectionAction(ContentVariant $contentVariant): array
    {
        if ($contentVariant->getType() !== VideoListSectionContentVariantType::TYPE) {
            throw new NotFoundHttpException();
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
            ]
        ];
    }

    /**
     * @Route("/item/{id}", name="oro_resource_library_videos_item", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function itemAction(ContentVariant $contentVariant): array
    {
        if ($contentVariant->getType() !== VideoListSectionItemContentVariantType::TYPE) {
            throw new NotFoundHttpException();
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
            ]
        ];
    }
}
