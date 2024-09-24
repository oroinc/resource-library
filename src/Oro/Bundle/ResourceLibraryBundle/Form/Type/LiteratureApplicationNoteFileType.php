<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\AttachmentBundle\Form\Type\FileType;
use Oro\Bundle\ResourceLibraryBundle\Entity\LiteratureApplicationNoteFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Represents content variant type form for literature and application note file
 */
class LiteratureApplicationNoteFileType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'file',
            FileType::class,
            [
                'label' => false,
                'required' => true,
                'checkEmptyFile' => true,
                'dynamic_fields_ignore_exception' => true
            ]
        );
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => LiteratureApplicationNoteFile::class]);
    }
}
