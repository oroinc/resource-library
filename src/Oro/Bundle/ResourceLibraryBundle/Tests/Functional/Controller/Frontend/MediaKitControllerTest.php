<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\RedirectBundle\Entity\Slug;
use Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures\LoadMediaKitTestData;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaKitControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->initClient();
        $this->loadFixtures([
            LoadMediaKitTestData::class,
        ]);
    }

    public function testListActionReturnsSuccessfulResponse()
    {
        // MediaKitListContentVariantType and MediaKitListItemContentVariantType share route
        // oro_resource_library_media_kit_list, so findOneBy(routeName) is non-deterministic.
        // Use the list node reference from media_kits_data.yml instead.
        /** @var ContentNode $contentNode */
        $contentNode = $this->getReference(LoadMediaKitTestData::MEDIA_KIT_LIST_NODE_REFERENCE_NAME);
        $contentVariant = $contentNode->getDefaultVariant();
        self::assertNotNull($contentVariant);

        /** @var Slug|false $slug */
        $slug = $contentVariant->getSlugs()->first();
        self::assertInstanceOf(Slug::class, $slug);

        $this->client->request(Request::METHOD_GET, $slug->getUrl());
        $response = $this->client->getResponse();

        static::assertResponseStatusCodeEquals($response, Response::HTTP_OK);
    }
}
