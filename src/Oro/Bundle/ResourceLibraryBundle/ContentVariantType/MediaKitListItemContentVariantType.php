<?php

namespace Oro\Bundle\ResourceLibraryBundle\ContentVariantType;

use Oro\Bundle\ResourceLibraryBundle\Form\Type\MediaKitListItemType;
use Oro\Component\Routing\RouteData;
use Oro\Component\WebCatalog\ContentVariantEntityProviderInterface;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;
use Oro\Component\WebCatalog\Entity\ContentVariantInterface;

/**
 * Provides content variant type for media kit item.
 */
class MediaKitListItemContentVariantType implements ContentVariantTypeInterface, ContentVariantEntityProviderInterface
{
    public const TYPE = 'media_kit_list_item';

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
        return 'oro.resourcelibrary.mediakit.content_variant_type.list_item.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType(): string
    {
        return MediaKitListItemType::class;
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
        return new RouteData('oro_resource_library_media_kit_list');
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

    /**
     * {@inheritdoc}
     */
    public function getAttachedEntity(ContentVariantInterface $contentVariant)
    {
        return $contentVariant->getMediaKit();
    }
}
