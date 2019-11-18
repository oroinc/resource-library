<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\ResourceLibraryBundle\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type to manage Video entity
 */
class VideoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'shortDescription',
            OroRichTextType::class,
            [
                'label' => 'oro.resourcelibrary.video.short_description.label',
                'required' => true,
            ]
        )->add(
            'description',
            OroRichTextType::class,
            [
                'label' => 'oro.resourcelibrary.video.description.label',
                'required' => true,
            ]
        )->add(
            'link',
            TextType::class,
            [
                'label' => 'oro.resourcelibrary.video.link.label',
                'required' => true,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Video::class]);
    }
}
