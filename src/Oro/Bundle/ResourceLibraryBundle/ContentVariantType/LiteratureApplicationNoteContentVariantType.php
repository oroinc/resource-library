<?php

namespace Oro\Bundle\ResourceLibraryBundle\ContentVariantType;

use Oro\Bundle\ResourceLibraryBundle\Form\Type\LiteratureApplicationNoteType;
use Oro\Component\Routing\RouteData;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;
use Oro\Component\WebCatalog\Entity\ContentVariantInterface;

/**
 * Provides content variant type for literature and application notes
 */
class LiteratureApplicationNoteContentVariantType implements ContentVariantTypeInterface
{
    public const TYPE = 'literature_application_note';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle(): string
    {
        return 'oro.resourcelibrary.literature_application_note.content_variant_type.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType(): string
    {
        return LiteratureApplicationNoteType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteData(ContentVariantInterface $contentVariant): RouteData
    {
        return new RouteData(
            'oro_resource_library_literature_application_note_index',
            ['id' => $contentVariant->getId()]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getApiResourceClassName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getApiResourceIdentifierDqlExpression($alias): string
    {
        return '';
    }
}
