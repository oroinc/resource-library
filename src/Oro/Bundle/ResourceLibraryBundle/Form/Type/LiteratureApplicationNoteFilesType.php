<?php

namespace Oro\Bundle\ResourceLibraryBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;

/**
 * Represents content variant type form for literature and application note files
 */
class LiteratureApplicationNoteFilesType extends AbstractType
{
    #[\Override]
    public function getParent(): string
    {
        return CollectionType::class;
    }
}
