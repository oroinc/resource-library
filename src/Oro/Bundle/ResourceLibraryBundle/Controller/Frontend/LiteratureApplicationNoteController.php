<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Provider\LiteratureNoteContentProvider;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Provides action for Literature & Application Notes pages
 */
class LiteratureApplicationNoteController extends AbstractController
{
    /**
     * @Route("/", name="oro_resource_library_literature_application_note_index", requirements={"id"="\d+"})
     * @Layout()
     *
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function indexAction(ContentVariant $contentVariant = null): array
    {
        // If ContentVariant has no appropriate literature type we consider it is not valid
        if (!$contentVariant || $contentVariant->getType() !== LiteratureApplicationNoteContentVariantType::TYPE) {
            throw $this->createNotFoundException();
        }

        return $this->get(LiteratureNoteContentProvider::class)->getContent($contentVariant);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            LiteratureNoteContentProvider::class,
        ]);
    }
}
