<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Attribute\Layout;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Provider\LiteratureNoteContentProvider;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Provides action for Literature & Application Notes pages
 */
class LiteratureApplicationNoteController extends AbstractController
{
    #[Route(path: '/', name: 'oro_resource_library_literature_application_note_index', requirements: ['id' => '\d+'])]
    #[Layout]
    public function indexAction(?ContentVariant $contentVariant = null): array
    {
        // If ContentVariant has no appropriate literature type we consider it is not valid
        if (!$contentVariant || $contentVariant->getType() !== LiteratureApplicationNoteContentVariantType::TYPE) {
            throw $this->createNotFoundException();
        }

        return $this->container->get(LiteratureNoteContentProvider::class)->getContent($contentVariant);
    }

    #[\Override]
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            LiteratureNoteContentProvider::class,
        ]);
    }
}
