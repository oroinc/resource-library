<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\ResourceLibraryBundle\Model\ExtendLiteratureApplicationNoteFile;

/**
 * Represents literature and application note page records
 *
 * @ORM\Entity()
 * @ORM\Table(name="oro_literature_note_file")
 * @Config()
 */
class LiteratureApplicationNoteFile extends ExtendLiteratureApplicationNoteFile
{
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
