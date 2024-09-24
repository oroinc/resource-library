<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\RedirectBundle\Entity\Repository\SlugRepository;
use Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures\LoadMediaKitTestData;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaKitControllerTest extends WebTestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $this->initClient();
        $this->loadFixtures([
            LoadMediaKitTestData::class,
        ]);
    }

    public function testListActionReturnsSuccessfulResponse()
    {
        $slug = static::getContainer()->get(SlugRepository::class)->findOneBy([
            'routeName' => 'oro_resource_library_media_kit_list',
        ]);

        $this->client->request(Request::METHOD_GET, $slug->getUrl());
        $response = $this->client->getResponse();

        static::assertResponseStatusCodeEquals($response, Response::HTTP_OK);
    }
}
