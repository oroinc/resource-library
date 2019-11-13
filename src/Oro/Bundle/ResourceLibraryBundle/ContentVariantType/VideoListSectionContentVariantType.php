<?php

namespace Oro\Bundle\ResourceLibraryBundle\ContentVariantType;

use Oro\Bundle\ResourceLibraryBundle\Form\Type\VideoListSectionType;
use Oro\Component\Routing\RouteData;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;
use Oro\Component\WebCatalog\Entity\ContentVariantInterface;

/**
 * Provides content variant type for videos section page.
 */
class VideoListSectionContentVariantType implements ContentVariantTypeInterface
{
    public const TYPE = 'video_list_section';

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
        return 'oro.resourcelibrary.video.content_variant_type.list_section.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType(): string
    {
        return VideoListSectionType::class;
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
    public function getRouteData(ContentVariantInterface $contentVariant)
    {
        return new RouteData('oro_resource_library_videos_section', ['id' => $contentVariant->getId()]);
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
