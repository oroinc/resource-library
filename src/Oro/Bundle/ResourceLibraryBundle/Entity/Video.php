<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;

/**
 * Holds video information.
 *
 * @ORM\Table(name="oro_rl_video")
 * @ORM\Entity
 * @Config(
 *      routeName="oro_resource_library_videos_index",
 *      defaultValues={
 *          "entity"={
 *              "icon"="fa-blog"
 *          },
 *          "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          }
 *      }
 * )
 */
class Video implements DatesAwareInterface, OrganizationAwareInterface
{
    use DatesAwareTrait;
    use OrganizationAwareTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text", nullable=false)
     */
    protected $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $link;

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param null|string $shortDescription
     * @return $this
     */
    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return null|string
     */
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

    /**
     * @return null|string
     */
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
