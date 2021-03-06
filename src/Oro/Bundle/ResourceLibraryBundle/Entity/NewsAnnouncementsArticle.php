<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityBundle\EntityProperty\CreatedAtAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\CreatedAtAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\ResourceLibraryBundle\Model\ExtendNewsAnnouncementsArticle;

/**
 * NewsAnnouncementsArticle entity
 *
 * @ORM\Entity(repositoryClass="Oro\Bundle\ResourceLibraryBundle\Entity\Repository\NewsAnnouncementsArticleRepository")
 * @ORM\Table(name="oro_news_announce_article")
 * @Config()
 */
class NewsAnnouncementsArticle extends ExtendNewsAnnouncementsArticle implements CreatedAtAwareInterface
{
    use CreatedAtAwareTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="description")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="short_description")
     */
    private $shortDescription;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return NewsAnnouncementsArticle
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     * @return NewsAnnouncementsArticle
     */
    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
