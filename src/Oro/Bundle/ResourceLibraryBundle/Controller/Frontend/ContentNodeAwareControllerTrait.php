<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Cache\ResolvedData\ResolvedContentNode;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

/**
 * Provides easy way to get ResolvedContentNode from the given ContentNode.
 */
trait ContentNodeAwareControllerTrait
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return \array_merge(parent::getSubscribedServices(), [
            ContentNodeTreeResolverInterface::class,
            ScopeManager::class,
            'doctrine' => ManagerRegistry::class,
        ]);
    }

    private function resolveTree(?ContentVariant $variant, string $expectedContentVariantType): ?ResolvedContentNode
    {
        if ($variant) {
            $scope = $this->container->get(ScopeManager::class)->findMostSuitable('web_content');
            if ($scope) {
                $resolved = $this->container->get(ContentNodeTreeResolverInterface::class)
                    ->getResolvedContentNode($variant->getNode(), $scope);

                if ($resolved && $resolved->getResolvedContentVariant()->getType() === $expectedContentVariantType) {
                    return $resolved;
                }
            }
        }

        throw $this->createNotFoundException();
    }
}
