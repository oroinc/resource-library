<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationPageContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Represents content variant type form for Safety Specification Page
 */
class SafetySpecificationPageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'description',
                OroRichTextType::class,
                [
                    'label' => 'oro.resourcelibrary.literature_application_note.form.description.label',
                    'required' => false,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return PageVariantType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'content_variant_type' => SafetySpecificationPageContentVariantType::TYPE,
        ]);
    }
}
