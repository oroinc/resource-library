<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteFileCollectionContentVariantType;
use Oro\Component\WebCatalog\Form\PageVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Represents content variant type form for literature and application notes
 */
class LiteratureApplicationNoteFileCollectionType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'literature_note_files',
            LiteratureApplicationNoteFilesType::class,
            [
                'label' => 'oro.resourcelibrary.literature_application_note.form.attachment.plural_label',
                'required' => true,
                'entry_type' => LiteratureApplicationNoteFileType::class,
                'constraints' => [new Count(['min' => 1])],
            ]
        );
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content_variant_type' => LiteratureApplicationNoteFileCollectionContentVariantType::TYPE,
        ]);
    }

    #[\Override]
    public function getParent(): string
    {
        return PageVariantType::class;
    }
}
