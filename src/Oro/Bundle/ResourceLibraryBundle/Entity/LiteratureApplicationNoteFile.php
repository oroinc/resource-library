<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Extend\Entity\Autocomplete\OroResourceLibraryBundle_Entity_LiteratureApplicationNoteFile;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityInterface;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityTrait;

/**
 * Represents literature and application note page records
 *
 * @ORM\Entity()
 * @ORM\Table(name="oro_literature_note_file")
 * @Config()
 *
 * @method null|File getFile()
 * @method LiteratureApplicationNoteFile setFile(File $file)
 * @mixin OroResourceLibraryBundle_Entity_LiteratureApplicationNoteFile
 */
class LiteratureApplicationNoteFile implements ExtendEntityInterface
{
    use ExtendEntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
