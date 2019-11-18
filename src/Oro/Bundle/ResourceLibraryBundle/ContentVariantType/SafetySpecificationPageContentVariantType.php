<?php

namespace Oro\Bundle\ResourceLibraryBundle\ContentVariantType;

use Oro\Bundle\ResourceLibraryBundle\Form\Type\SafetySpecificationPageType;
use Oro\Component\Routing\RouteData;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;
use Oro\Component\WebCatalog\Entity\ContentVariantInterface;

/**
 * Provides content variant type for Safety Specifications
 */
class SafetySpecificationPageContentVariantType implements ContentVariantTypeInterface
{
    public const TYPE = 'safety_specification_page';

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
        return 'oro.resourcelibrary.safety_specification.content_variant_type_page.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType(): string
    {
        return SafetySpecificationPageType::class;
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
        return new RouteData('oro_resource_library_safety_specification_index', ['id' => $contentVariant->getId()]);
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
