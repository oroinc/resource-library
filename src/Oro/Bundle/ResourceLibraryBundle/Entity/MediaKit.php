<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Extend\Entity\Autocomplete\OroResourceLibraryBundle_Entity_MediaKit;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Attribute\Config;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityInterface;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityTrait;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;

/**
 * Holds media kit information.
 *
 * @method null|File getBanner()
 * @method MediaKit setBanner(File $image)
 * @method null|File getMediaKitFile()
 * @method MediaKit setMediaKitFile(File $mediaKitFile)
 * @method null|File getLogoPackageFile()
 * @method MediaKit setLogoPackageFile(File $logoPackageFile)
 * @mixin OroResourceLibraryBundle_Entity_MediaKit
 */
#[ORM\Entity]
#[ORM\Table(name: 'oro_rl_media_kit')]
#[Config(
    defaultValues: [
        'entity' => ['icon' => 'fa-blog'],
        'ownership' => [
            'owner_type' => 'ORGANIZATION',
            'owner_field_name' => 'organization',
            'owner_column_name' => 'organization_id'
        ],
        'security' => ['type' => 'ACL', 'group_name' => '']
    ]
)]
class MediaKit implements DatesAwareInterface, OrganizationAwareInterface, ExtendEntityInterface
{
    use DatesAwareTrait;
    use OrganizationAwareTrait;
    use ExtendEntityTrait;

    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    protected ?string $description = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    protected ?string $link = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param null|string $link
     * @return $this
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
