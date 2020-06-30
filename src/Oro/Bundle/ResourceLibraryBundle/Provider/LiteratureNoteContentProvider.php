<?php

namespace Oro\Bundle\ResourceLibraryBundle\Provider;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteFileCollectionContentVariantType;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Cache\ResolvedData\ResolvedContentVariant;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

/**
 * Gets a ContentNode for passed ContentVariant
 * Applies scope checks for child nodes and content variants and returns which will be used in the template
 */
class LiteratureNoteContentProvider
{
    /** @var ContentNodeTreeResolverInterface */
    private $contentNodeTreeResolver;

    /** @var ScopeManager */
    private $scopeManager;

    /** @var DoctrineHelper */
    private $doctrineHelper;

    /**
     * @param ContentNodeTreeResolverInterface $contentNodeTreeResolver
     * @param ScopeManager $scopeManager
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(
        ContentNodeTreeResolverInterface $contentNodeTreeResolver,
        ScopeManager $scopeManager,
        DoctrineHelper $doctrineHelper
    ) {
        $this->contentNodeTreeResolver = $contentNodeTreeResolver;
        $this->scopeManager = $scopeManager;
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * @param ContentVariant $contentVariant
     * @return array
     */
    public function getContent(ContentVariant $contentVariant): array
    {
        $contentNode = $contentVariant->getNode();
        $scope = $this->scopeManager->findMostSuitable('web_content');

        $resolvedContentNode = $scope
            ? $this->contentNodeTreeResolver->getResolvedContentNode($contentNode, $scope)
            : null;

        $resolvedContentVariantIds = [];
        $fileSections = [
            'data' => []
        ];

        if ($resolvedContentNode) {
            foreach ($resolvedContentNode->getChildNodes() as $childNode) {

                /** @var ResolvedContentVariant $resolvedContentVariant */
                $resolvedContentVariant = $childNode->getResolvedContentVariant();

                if ($resolvedContentVariant->getType() !==
                    LiteratureApplicationNoteFileCollectionContentVariantType::TYPE
                ) {
                    continue;
                };

                $resolvedContentVariantIds[] = $resolvedContentVariant->getId();

                $fileSections['data'][$childNode->getId()]['childNode'] = $childNode;
            }
        }

        if ($resolvedContentVariantIds) {
            $contentVariantEntities = $this->doctrineHelper
                ->createQueryBuilder(ContentVariant::class, 'variant')
                ->where('variant.id IN (:ids)')
                ->setParameter('ids', $resolvedContentVariantIds)
                ->getQuery()
                ->getResult();

            foreach ($contentVariantEntities as $entity) {
                $nodeId = $entity->getNode()->getId();

                $fileSections['data'][$nodeId]['filesCollection'] = $entity->getLiteratureNoteFiles();
            }
        }

        return [
            'data' => [
                'contentVariant' => $contentVariant,
                'contentNode' => $contentNode,
                'fileSections' => $fileSections
            ]
        ];
    }
}
