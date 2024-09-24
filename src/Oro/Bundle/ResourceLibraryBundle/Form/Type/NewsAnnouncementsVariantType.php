<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Represents content variant type form for news and announcements
 */
class NewsAnnouncementsVariantType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'description',
            OroRichTextType::class,
            [
                'label' => 'oro.webcatalog.contentvariant.description.label',
                'required' => false,
            ]
        );
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content_variant_type' => NewsAnnouncementsContentVariantType::TYPE,
        ]);
    }

    #[\Override]
    public function getParent(): string
    {
        return PageVariantType::class;
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'oro_news_announcements';
    }
}
