<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationSectionContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Represents content variant type form for Safety Specification Section
 */
class SafetySpecificationSectionType extends AbstractType
{
    #[\Override]
    public function getParent(): ?string
    {
        return PageVariantType::class;
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'content_variant_type' => SafetySpecificationSectionContentVariantType::TYPE,
        ]);
    }
}
