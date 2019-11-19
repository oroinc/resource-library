<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\AttachmentBundle\Form\Type\ImageType;
use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\ResourceLibraryBundle\Entity\NewsAnnouncementsArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Represents form for news and announcements article entity
 */
class NewsAnnouncementsArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'description',
                OroRichTextType::class,
                [
                    'label' => 'oro.resourcelibrary.newsannouncementsarticle.description.label',
                    'constraints' => [new NotBlank()],
                ]
            )
            ->add(
                'short_description',
                OroRichTextType::class,
                [
                    'label' => 'oro.resourcelibrary.newsannouncementsarticle.short_description.label',
                    'constraints' => [new NotBlank()],
                ]
            )
            ->add(
                'image',
                ImageType::class,
                [
                    'label' => 'oro.resourcelibrary.newsannouncementsarticle.image.label',
                    'constraints' => [new Valid()],
                    'required' => false,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewsAnnouncementsArticle::class,
        ]);
    }
}
