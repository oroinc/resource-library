<?php

namespace Oro\Bundle\ResourceLibraryBundle\EventListener;

use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\DataGridBundle\Event\PreBuild;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\NewsAnnouncementsArticleContentVariantType;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\Repository\ContentVariantRepository;

/**
 * Provide content variant ids with applied restriction for article datagrid
 */
class NewsAnnouncementsArticleDatagridEventListener
{
    private ManagerRegistry $doctrine;
    private ScopeManager $scopeManager;

    public function __construct(ScopeManager $scopeManager, ManagerRegistry $doctrine)
    {
        $this->scopeManager = $scopeManager;
        $this->doctrine = $doctrine;
    }

    /**
     * @throws \Exception
     */
    public function onPreBuild(PreBuild $event): void
    {
        $params = $event->getParameters();
        if (!$params->has('parentNodeId')) {
            return;
        }

        /** @var ContentVariantRepository $repository */
        $repository = $this->doctrine->getRepository(ContentVariant::class);

        $params->set('today', new \DateTime('today', new \DateTimeZone('UTC')));
        $params->set(
            'variantIds',
            $repository->findChildrenVariantIds(
                $params->get('parentNodeId'),
                $this->scopeManager->getCriteria('web_content'),
                NewsAnnouncementsArticleContentVariantType::TYPE
            )
        );
    }
}
