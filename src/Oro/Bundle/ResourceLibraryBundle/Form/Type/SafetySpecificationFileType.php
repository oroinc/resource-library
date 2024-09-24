<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\AttachmentBundle\Form\Type\FileType;
use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\SafetySpecificationFileContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Represents content variant type form for Safety Specification File
 */
class SafetySpecificationFileType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'description',
                OroRichTextType::class,
                [
                    'label' => 'oro.resourcelibrary.safety_specification.form.description.label',
                    'required' => true,
                    'constraints' => [new NotBlank()]
                ]
            )
            ->add(
                'pdf_file',
                FileType::class,
                [
                    'label' => 'oro.resourcelibrary.safety_specification.form.pdf_file.label',
                    'required' => true,
                    'checkEmptyFile' => true,
                    'dynamic_fields_ignore_exception' => true
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
        $resolver->setDefaults([
            'content_variant_type' => SafetySpecificationFileContentVariantType::TYPE,
        ]);
    }
}
