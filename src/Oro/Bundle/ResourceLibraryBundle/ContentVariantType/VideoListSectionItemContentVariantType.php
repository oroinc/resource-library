<?php

namespace Oro\Bundle\ResourceLibraryBundle\ContentVariantType;

use Oro\Bundle\ResourceLibraryBundle\Form\Type\VideoListSectionItemType;
use Oro\Component\Routing\RouteData;
use Oro\Component\WebCatalog\ContentVariantEntityProviderInterface;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;
use Oro\Component\WebCatalog\Entity\ContentVariantInterface;

/**
 * Provides content variant type for videos section item page.
 */
class VideoListSectionItemContentVariantType implements
    ContentVariantTypeInterface,
    ContentVariantEntityProviderInterface
{
    public const TYPE = 'video_list_section_item';

    #[\Override]
    public function getName(): string
    {
        return self::TYPE;
    }

    #[\Override]
    public function getTitle(): string
    {
        return 'oro.resourcelibrary.video.content_variant_type.list_section_item.label';
    }

    #[\Override]
    public function getFormType(): string
    {
        return VideoListSectionItemType::class;
    }

    #[\Override]
    public function isAllowed(): bool
    {
        return true;
    }

    #[\Override]
    public function getRouteData(ContentVariantInterface $contentVariant)
    {
        return new RouteData('oro_resource_library_videos_item', ['id' => $contentVariant->getId()]);
    }

    #[\Override]
    public function getApiResourceClassName(): string
    {
        return '';
    }

    #[\Override]
    public function getApiResourceIdentifierDqlExpression($alias): string
    {
        return '';
    }

    #[\Override]
    public function getAttachedEntity(ContentVariantInterface $contentVariant)
    {
        return $contentVariant->getVideo();
    }
}
