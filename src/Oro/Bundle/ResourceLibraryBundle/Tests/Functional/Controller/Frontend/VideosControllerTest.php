<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\RedirectBundle\Entity\Repository\SlugRepository;
use Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures\LoadVideosTestData;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VideosControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->initClient();
        $this->loadFixtures([
            LoadVideosTestData::class,
        ]);
    }

    public function testListActionReturnsSuccessfulResponse()
    {
        $slug = static::getContainer()->get(SlugRepository::class)->findOneBy([
            'routeName' => 'oro_resource_library_videos_list',
        ]);

        $this->client->request(Request::METHOD_GET, $slug->getUrl());
        $response = $this->client->getResponse();

        static::assertResponseStatusCodeEquals($response, Response::HTTP_OK);
    }
}
