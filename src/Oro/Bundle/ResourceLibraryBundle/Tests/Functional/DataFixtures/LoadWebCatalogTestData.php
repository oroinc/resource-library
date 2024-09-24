<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;

class LoadWebCatalogTestData extends AbstractLoadWebCatalogTestData
{
    public const WEB_CATALOG_REFERENCE_NAME = 'web_catalog';
    public const WEB_CATALOG_NODE_REFERENCE_NAME = 'web_catalog_node';

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $webCatalog = $this->createCatalog($manager);
        $contentNodes = $this->getWebCatalogData(
            '@OroResourceLibraryBundle/Tests/Functional/DataFixtures/data/web_catalog_data.yml'
        );
        $this->loadContentNodes($manager, $webCatalog, $contentNodes);
        $manager->flush();

        $this->enableWebCatalog($webCatalog);
        $this->generateCache($webCatalog);

        $this->setReference('web_catalog', $webCatalog);
    }

    private function createCatalog(ObjectManager $manager): WebCatalog
    {
        /** @var EntityManager $manager */
        $user = $this->getFirstUser($manager);
        $businessUnit = $user->getOwner();
        $organization = $user->getOrganization();

        $webCatalog = new WebCatalog();
        $webCatalog->setName('New Web Catalog');
        $webCatalog->setDescription('New Description');
        $webCatalog->setOwner($businessUnit);
        $webCatalog->setOrganization($organization);

        $manager->persist($webCatalog);
        $manager->flush($webCatalog);

        return $webCatalog;
    }
}
