<?php

namespace Oro\Bundle\ResourceLibraryBundle\EventListener;

use Doctrine\Bundle\DoctrineBundle\Registry;
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
    /** @var Registry */
    private $registry;

    /** @var ScopeManager */
    private $scopeManager;

    /**
     * @param ScopeManager $scopeManager
     * @param Registry $registry
     */
    public function __construct(ScopeManager $scopeManager, Registry $registry)
    {
        $this->scopeManager = $scopeManager;
        $this->registry = $registry;
    }

    /**
     * @param PreBuild $event
     * @throws \Exception
     */
    public function onPreBuild(PreBuild $event): void
    {
        $params = $event->getParameters();
        if (!$params->has('parentNodeId')) {
            return;
        }

        /** @var ContentVariantRepository $repository */
        $repository = $this->registry->getRepository(ContentVariant::class);

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
