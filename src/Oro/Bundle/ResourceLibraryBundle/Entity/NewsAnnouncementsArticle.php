<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Extend\Entity\Autocomplete\OroResourceLibraryBundle_Entity_NewsAnnouncementsArticle;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\EntityBundle\EntityProperty\CreatedAtAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\CreatedAtAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Attribute\Config;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityInterface;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityTrait;
use Oro\Bundle\ResourceLibraryBundle\Entity\Repository\NewsAnnouncementsArticleRepository;

/**
 * NewsAnnouncementsArticle entity
 *
 *
 * @method File|null getImage()
 * @method NewsAnnouncementsArticle setImage(?File $image)
 * @mixin OroResourceLibraryBundle_Entity_NewsAnnouncementsArticle
 */
#[ORM\Entity(repositoryClass: NewsAnnouncementsArticleRepository::class)]
#[ORM\Table(name: 'oro_news_announce_article')]
#[Config]
class NewsAnnouncementsArticle implements CreatedAtAwareInterface, ExtendEntityInterface
{
    use CreatedAtAwareTrait;
    use ExtendEntityTrait;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'description', type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(name: 'short_description', type: Types::TEXT)]
    private ?string $shortDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
