<?php

namespace Oro\Bundle\ResourceLibraryBundle\EventListener;

use Oro\Bundle\DataGridBundle\Event\PreBuild;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Oro\Bundle\WebCatalogBundle\Provider\ContentNodeProvider;

/**
 * Applies scope restrictions to the query of ContentVariant grid.
 */
class VideoListSectionDatagridEventListener
{
    /** @var ContentNodeProvider */
    private $contentNodeProvider;

    /** @var DoctrineHelper */
    private $doctrineHelper;

    public function __construct(ContentNodeProvider $contentNodeProvider, DoctrineHelper $doctrineHelper)
    {
        $this->contentNodeProvider = $contentNodeProvider;
        $this->doctrineHelper = $doctrineHelper;
    }

    public function onPreBuild(PreBuild $event): void
    {
        $params = $event->getParameters();

        $qb = $this->doctrineHelper->createQueryBuilder(ContentNode::class, 'node');
        $qb->andWhere($qb->expr()->eq('node.parentNode', ':parentNode'))
            ->setParameter('parentNode', $params->get('contentNodeId'));

        $ids = $this->contentNodeProvider->getContentNodeIds($qb);

        $params->set('variantIds', $this->contentNodeProvider->getContentVariantIds($ids));
    }
}
