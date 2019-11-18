<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\VideoListSectionItemContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Form type to manage video list section item page variant type
 */
class VideoListSectionItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'video',
            VideoType::class,
            [
                'label' => 'oro.resourcelibrary.video.entity_label',
                'required' => true,
                'constraints' => [new Valid()]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['content_variant_type' => VideoListSectionItemContentVariantType::TYPE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return PageVariantType::class;
    }
}
