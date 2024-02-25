<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use LogicException;
use Oro\Bundle\CatalogBundle\ContentVariantType\CategoryPageContentVariantType;
use Oro\Bundle\CatalogBundle\Entity\Category;
use Oro\Bundle\CMSBundle\ContentVariantType\CmsPageContentVariantType;
use Oro\Bundle\CMSBundle\Entity\Page;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\EntityExtendBundle\PropertyAccess;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\ProductBundle\ContentVariantType\ProductCollectionContentVariantType;
use Oro\Bundle\ScopeBundle\Entity\Scope;
use Oro\Bundle\SegmentBundle\Entity\Segment;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WebCatalogBundle\Cache\ContentNodeTreeCacheDumper;
use Oro\Bundle\WebCatalogBundle\ContentVariantType\SystemPageContentVariantType;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractLoadWebCatalogTestData extends AbstractFixture implements ContainerAwareInterface
{
    protected ContainerInterface $container;

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    protected function loadContentNodes(
        ObjectManager $manager,
        WebCatalog $webCatalog,
        array $nodes,
        ContentNode $parent = null
    ): void {
        foreach ($nodes as $name => $contentNode) {
            $node = new ContentNode();
            $node->setWebCatalog($webCatalog);
            $title = new LocalizedFallbackValue();
            $title->setString($contentNode['defaultTitle']);
            $node->setDefaultTitle($title);
            $slug = new LocalizedFallbackValue();
            $slug->setString($contentNode['defaultSlugPrototype']);
            $node->setDefaultSlugPrototype($slug);

            if ($parent) {
                $node->setParentNode($parent);
            }

            $isParentScopeUsed = !empty($contentNode['parentScopeUsed']);
            $node->setParentScopeUsed($isParentScopeUsed);

            if ($isParentScopeUsed) {
                foreach ($node->getParentNode()->getScopes() as $scope) {
                    $node->addScope($scope);
                }
            } else {
                foreach ($contentNode['scopes'] as $scope) {
                    $scope = $this->getScope($scope, $webCatalog);
                    $node->addScope($scope);
                }
            }
            $this->addContentVariants($webCatalog, $contentNode['contentVariants'], $node);

            $manager->persist($node);
            $manager->flush($node);
            $this->resolveScopes($node);
            $this->generateSlugs($node);

            //Adds possibility to work with nodes by reference name if needed
            if (!empty($contentNode['setReference'])) {
                $this->setReference($contentNode['setReference'], $node);
            }

            if (isset($contentNode['children'])) {
                $this->loadContentNodes($manager, $webCatalog, $contentNode['children'], $node);
            }

            if (isset($contentNode['isNavigationRoot'])) {
                $this->setNavigationRoot($node);
            }
        }

        $manager->flush();
    }

    protected function setNavigationRoot(ContentNode $node): void
    {
        /** @var ConfigManager $configManager */
        $configManager = $this->container->get('oro_config.global');
        $configManager->set('oro_web_catalog.navigation_root', $node->getId());
        $configManager->flush();
    }

    protected function enableWebCatalog(WebCatalog $webCatalog): void
    {
        $configManager = $this->container->get('oro_config.global');
        $configManager->set('oro_web_catalog.web_catalog', $webCatalog->getId());
        $configManager->flush();
    }

    protected function generateSlugs(ContentNode $contentNode): void
    {
        $this->container->get('oro_web_catalog.generator.slug_generator')
            ->generate($contentNode);
    }

    protected function getScope(array $criteria, WebCatalog $webCatalog): Scope
    {
        $scopeCriteria = array_merge($criteria, ['webCatalog' => $webCatalog]);

        return $this->container->get('oro_scope.scope_manager')->findOrCreate('base_scope', $scopeCriteria);
    }

    protected function getContentVariant($type, array $params): ContentVariant
    {
        $variant = new ContentVariant();

        $variant->setType($type);

        $accessor = PropertyAccess::createPropertyAccessor();
        $doctrine = $this->container->get('doctrine');
        if ($type === CategoryPageContentVariantType::TYPE
            && $accessor->isWritable($variant, 'category_page_category')) {
            $category = $this->getCategory($params['title']);
            $accessor->setValue($variant, 'category_page_category', $category);
            $variant->setExcludeSubcategories($params['excludeSubcategories'] ?? true);
        } elseif ($type === CmsPageContentVariantType::TYPE && $accessor->isWritable($variant, 'cms_page')) {
            $page = $doctrine
                ->getRepository(Page::class)
                ->findOneByTitle($params['title']);
            $accessor->setValue($variant, 'cms_page', $page);
        } elseif ($type === SystemPageContentVariantType::TYPE) {
            $variant->setSystemPageRoute($params['route']);
        } elseif ($type === ProductCollectionContentVariantType::TYPE
            && $accessor->isWritable($variant, 'product_collection_segment')
        ) {
            $segment = $doctrine
                ->getRepository(Segment::class)
                ->findOneByName($params['title']);
            $accessor->setValue($variant, 'product_collection_segment', $segment);
        }

        return $variant;
    }

    private function getCategory(string  $title): Category
    {
        $doctrine = $this->container->get('doctrine');
        $organization = $doctrine->getRepository(Organization::class)->getFirst();
        $queryBuilder = $doctrine->getRepository(Category::class)->findOneByDefaultTitleQueryBuilder($title);
        $queryBuilder
            ->andWhere('category.organization = :organization')
            ->setParameter('organization', $organization);

        return $queryBuilder->getQuery()->getSingleResult();
    }

    protected function getWebCatalogData(string $filePath): array
    {
        $locator = $this->container->get('file_locator');
        $fileName = $locator->locate($filePath);

        return Yaml::parse(file_get_contents($fileName));
    }

    protected function resolveScopes(ContentNode $contentNode): void
    {
        $this->container->get('oro_web_catalog.resolver.default_variant_scope')
            ->resolve($contentNode);
    }

    protected function addContentVariants(WebCatalog $webCatalog, array $contentVariantsData, ContentNode $node): void
    {
        foreach ($contentVariantsData as $contentVariant) {
            $variant = $this->getContentVariant($contentVariant['type'], $contentVariant['params']);
            $isDefault = !empty($contentVariant['isDefault']);
            $variant->setDefault($isDefault);
            if (!$isDefault) {
                foreach ($contentVariant['scopes'] as $scope) {
                    $scope = $this->getScope($scope, $webCatalog);
                    $variant->addScope($scope);
                }
            }
            $node->addContentVariant($variant);
        }
    }

    protected function generateCache(WebCatalog $webCatalog): void
    {
        /** @var ContentNodeTreeCacheDumper $dumper */
        $dumper = $this->container->get('oro_web_catalog.content_node_tree_cache_dumper');
        $dumper->dumpForAllScopes($webCatalog);
    }

    protected function getFirstUser(ObjectManager $manager): User
    {
        $users = $manager->getRepository(User::class)->findBy([], ['id' => 'ASC'], 1);
        if (!$users) {
            throw new LogicException('There are no users in system');
        }

        return reset($users);
    }
}
