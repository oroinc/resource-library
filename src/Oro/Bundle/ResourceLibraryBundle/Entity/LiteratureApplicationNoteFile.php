<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Extend\Entity\Autocomplete\OroResourceLibraryBundle_Entity_LiteratureApplicationNoteFile;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\EntityConfigBundle\Metadata\Attribute\Config;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityInterface;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityTrait;

/**
 * Represents literature and application note page records
 *
 *
 * @method null|File getFile()
 * @method LiteratureApplicationNoteFile setFile(File $file)
 * @mixin OroResourceLibraryBundle_Entity_LiteratureApplicationNoteFile
 */
#[ORM\Entity]
#[ORM\Table(name: 'oro_literature_note_file')]
#[Config]
class LiteratureApplicationNoteFile implements ExtendEntityInterface
{
    use ExtendEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
