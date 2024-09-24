<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsArticleContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Represents content variant type form for news and announcements article
 */
class NewsAnnouncementsArticleVariantType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'news_announcements_article',
            NewsAnnouncementsArticleType::class,
            [
                'constraints' => [new Valid()]
            ]
        );
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content_variant_type' => NewsAnnouncementsArticleContentVariantType::TYPE,
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
        return 'oro_news_announcements_article';
    }
}
