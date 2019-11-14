<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\AttachmentBundle\Form\Type\FileType;
use Oro\Bundle\AttachmentBundle\Form\Type\ImageType;
use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\ResourceLibraryBundle\Entity\MediaKit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type to manage media kit list item page variant type
 */
class MediaKitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'banner',
            ImageType::class,
            [
                'label' => 'oro.resourcelibrary.mediakit.banner.label',
                'required' => true,
                //'checkEmptyFile' => true
            ]
        )->add(
            'description',
            OroRichTextType::class,
            [
                'label' => 'oro.resourcelibrary.mediakit.description.label',
                'required' => true,
            ]
        )->add(
            'link',
            TextType::class,
            [
                'label' => 'oro.resourcelibrary.mediakit.link.label',
                'required' => true,
            ]
        )->add(
            'media_kit_file',
            FileType::class,
            [
                'label' => 'oro.resourcelibrary.mediakit.media_kit_file.label',
                'required' => true,
                //'checkEmptyFile' => true
            ]
        )->add(
            'logo_package_file',
            FileType::class,
            [
                'label' => 'oro.resourcelibrary.mediakit.logo_package_file.label',
                'required' => false,
                //'checkEmptyFile' => true
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => MediaKit::class]);
    }
}
