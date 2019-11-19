<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Represents content variant type form for news and announcements
 */
class NewsAnnouncementsVariantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content_variant_type' => NewsAnnouncementsContentVariantType::TYPE,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return PageVariantType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'oro_news_announcements';
    }
}
