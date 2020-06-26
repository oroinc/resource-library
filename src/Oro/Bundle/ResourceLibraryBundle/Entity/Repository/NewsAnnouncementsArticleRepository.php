<?php

namespace Oro\Bundle\ResourceLibraryBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityRepository;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsArticleContentVariantType;
use Oro\Bundle\ScopeBundle\Model\ScopeCriteria;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\Repository\ContentVariantRepository;

/**
 * Contains business specific methods for retrieving article entities.
 */
class NewsAnnouncementsArticleRepository extends EntityRepository
{
    /**
     * @param ContentVariant $variant
     * @param ScopeCriteria $criteria
     *
     * @return Collection|ContentVariant[]
     */
    public function findAlsoInterestingArticles(ContentVariant $variant, ScopeCriteria $criteria): Collection
    {
        $variantIds = $this->getVariantRepository()->findChildrenVariantIds(
            $variant->getNode()->getParentNode()->getId(),
            $criteria,
            NewsAnnouncementsArticleContentVariantType::TYPE
        );

        unset($variantIds[$variant->getNode()->getId()]);

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('content_variant', 'content_node', 'article')
            ->from(ContentVariant::class, 'content_variant')
            ->innerJoin('content_variant.news_announcements_article', 'article')
            ->innerJoin('content_variant.node', 'content_node')
            ->where(
                $qb->expr()->in('content_variant.id', ':variantIds'),
                $qb->expr()->lte('article.createdAt', ':createdAt')
            )
            ->setParameter('variantIds', $variantIds)
            ->setParameter(
                'createdAt',
                $variant->getNewsAnnouncementsArticle()->getCreatedAt(),
                Types::DATETIME_MUTABLE
            )
            ->orderBy('article.createdAt', 'DESC')
            ->setMaxResults(2);

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    /**
     * @param ContentVariant $variant
     * @param ScopeCriteria $criteria
     *
     * @return Collection|ContentVariant[]
     */
    public function findTodayArticles(ContentVariant $variant, ScopeCriteria $criteria): Collection
    {
        $variantIds = $this->getVariantRepository()->findChildrenVariantIds(
            $variant->getNode()->getId(),
            $criteria,
            NewsAnnouncementsArticleContentVariantType::TYPE
        );

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('content_variant', 'content_node', 'article')
            ->from(ContentVariant::class, 'content_variant')
            ->innerJoin('content_variant.news_announcements_article', 'article')
            ->innerJoin('content_variant.node', 'content_node')
            ->where(
                $qb->expr()->in('content_variant.id', ':variantIds'),
                $qb->expr()->between('article.createdAt', ':createdAtFrom', ':createdAtTo')
            )
            ->setParameter('variantIds', $variantIds)
            ->setParameter('createdAtFrom', new \DateTime('today', new \DateTimeZone('UTC')), Types::DATETIME_MUTABLE)
            ->setParameter('createdAtTo', new \DateTime('tomorrow', new \DateTimeZone('UTC')), Types::DATETIME_MUTABLE)
            ->orderBy('article.createdAt', 'DESC');

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    /**
     * @param int $nodeId
     * @param ScopeCriteria $criteria
     *
     * @return Collection|ContentVariant[]
     */
    public function findLatest(int $nodeId, ScopeCriteria $criteria): Collection
    {
        $variantIds = $this->getVariantRepository()->findChildrenVariantIds(
            $nodeId,
            $criteria,
            NewsAnnouncementsArticleContentVariantType::TYPE
        );

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('content_variant', 'content_node', 'article')
            ->from(ContentVariant::class, 'content_variant')
            ->innerJoin('content_variant.news_announcements_article', 'article')
            ->innerJoin('content_variant.node', 'content_node')
            ->where(
                $qb->expr()->in('content_variant.id', ':variantIds')
            )
            ->setParameter('variantIds', $variantIds)
            ->orderBy('article.createdAt', 'DESC')
            ->setMaxResults(5);

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    /**
     * @return ContentVariantRepository
     */
    private function getVariantRepository(): ContentVariantRepository
    {
        return $this->getEntityManager()->getRepository(ContentVariant::class);
    }
}
