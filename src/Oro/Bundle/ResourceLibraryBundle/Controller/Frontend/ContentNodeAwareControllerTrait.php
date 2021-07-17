<?php

namespace Oro\Bundle\ResourceLibraryBundle\Controller\Frontend;

use Oro\Bundle\ScopeBundle\Manager\ScopeManager;
use Oro\Bundle\WebCatalogBundle\Cache\ResolvedData\ResolvedContentNode;
use Oro\Bundle\WebCatalogBundle\ContentNodeUtils\ContentNodeTreeResolverInterface;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;

/**
 * Provides easy way to get ResolvedContentNode from the given ContentNode.
 */
trait ContentNodeAwareControllerTrait
{
    use ControllerTrait;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return \array_merge(parent::getSubscribedServices(), [
            ContentNodeTreeResolverInterface::class,
            ScopeManager::class,
        ]);
    }

    private function resolveTree(?ContentVariant $variant, string $expectedContentVariantType): ?ResolvedContentNode
    {
        if ($variant) {
            $scope = $this->get(ScopeManager::class)->findMostSuitable('web_content');
            if ($scope) {
                $resolved = $this->get(ContentNodeTreeResolverInterface::class)
                    ->getResolvedContentNode($variant->getNode(), $scope);

                if ($resolved && $resolved->getResolvedContentVariant()->getType() === $expectedContentVariantType) {
                    return $resolved;
                }
            }
        }

        throw $this->createNotFoundException();
    }
}
