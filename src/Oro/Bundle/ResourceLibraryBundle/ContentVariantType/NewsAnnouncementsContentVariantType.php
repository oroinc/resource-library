<?php

namespace Oro\Bundle\ResourceLibraryBundle\ContentVariantType;

use Oro\Bundle\ResourceLibraryBundle\Form\Type\NewsAnnouncementsVariantType;
use Oro\Component\Routing\RouteData;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;
use Oro\Component\WebCatalog\Entity\ContentVariantInterface;

/**
 * Provides content variant type for news and announcements main page
 */
class NewsAnnouncementsContentVariantType implements ContentVariantTypeInterface
{
    public const TYPE = 'oro_news_announcements';

    #[\Override]
    public function getName(): string
    {
        return self::TYPE;
    }

    #[\Override]
    public function getTitle(): string
    {
        return 'oro.resourcelibrary.news_announcements.content_variant_type.label';
    }

    #[\Override]
    public function getFormType(): string
    {
        return NewsAnnouncementsVariantType::class;
    }

    #[\Override]
    public function isAllowed(): bool
    {
        return true;
    }

    #[\Override]
    public function getRouteData(ContentVariantInterface $contentVariant): RouteData
    {
        return new RouteData('oro_resource_library_news_announcements_index', ['id' => $contentVariant->getId()]);
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
}
