<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\MediaKitListItemContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Form type to manage media kit list item page variant type
 */
class MediaKitListItemType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'mediaKit',
            MediaKitType::class,
            [
                'label' => 'oro.resourcelibrary.mediakit.entity_label',
                'required' => true,
                'constraints' => [new Valid()],
            ]
        );
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['content_variant_type' => MediaKitListItemContentVariantType::TYPE]);
    }

    #[\Override]
    public function getParent(): ?string
    {
        return PageVariantType::class;
    }
}
