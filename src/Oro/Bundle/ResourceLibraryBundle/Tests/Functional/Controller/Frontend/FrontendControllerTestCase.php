<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\ConfigBundle\Tests\Functional\Traits\ConfigManagerAwareTestTrait;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

abstract class FrontendControllerTestCase extends WebTestCase
{
    use ConfigManagerAwareTestTrait;

    private ?int $initialNavigationRootId;
    private ?int $initialWebCatalogId;

    #[\Override]
    protected function setUp(): void
    {
        $this->initClient();

        $configManager = self::getConfigManager();
        $this->initialNavigationRootId = $configManager->get('oro_web_catalog.navigation_root');
        $this->initialWebCatalogId = $configManager->get('oro_web_catalog.web_catalog');
    }

    #[\Override]
    protected function tearDown(): void
    {
        $configManager = self::getConfigManager();
        $configManager->set('oro_web_catalog.navigation_root', $this->initialNavigationRootId);
        $configManager->set('oro_web_catalog.web_catalog', $this->initialWebCatalogId);
        $configManager->flush();
    }
}
