<?php

namespace Oro\Bundle\ResourceLibraryBundle\ContentVariantType;

use Oro\Bundle\ResourceLibraryBundle\Form\Type\SafetySpecificationFileType;
use Oro\Component\Routing\RouteData;
use Oro\Component\WebCatalog\ContentVariantEntityProviderInterface;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;
use Oro\Component\WebCatalog\Entity\ContentVariantInterface;

/**
 * Provides content variant type for Safety Specifications File
 */
class SafetySpecificationFileContentVariantType implements
    ContentVariantTypeInterface,
    ContentVariantEntityProviderInterface
{
    public const TYPE = 'safety_specification_file';

    #[\Override]
    public function getName(): string
    {
        return self::TYPE;
    }

    #[\Override]
    public function getTitle(): string
    {
        return 'oro.resourcelibrary.safety_specification.content_variant_type_file.label';
    }

    #[\Override]
    public function getFormType(): string
    {
        return SafetySpecificationFileType::class;
    }

    #[\Override]
    public function isAllowed(): bool
    {
        return true;
    }

    #[\Override]
    public function getRouteData(ContentVariantInterface $contentVariant): RouteData
    {
        return new RouteData('oro_resource_library_safety_specification_index');
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
        return $contentVariant->getPdfFile();
    }
}
