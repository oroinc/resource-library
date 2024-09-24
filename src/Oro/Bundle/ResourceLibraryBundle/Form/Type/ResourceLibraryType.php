<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\ResourceLibraryContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Represents content variant type form for the Resource Library.
 */
class ResourceLibraryType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'description',
            OroRichTextType::class,
            [
                'label' => 'oro.resourcelibrary.form.description.label',
                'required' => false,
            ]
        );
    }

    #[\Override]
    public function getParent(): ?string
    {
        return PageVariantType::class;
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['content_variant_type' => ResourceLibraryContentVariantType::TYPE]);
    }
}
