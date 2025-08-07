<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\RedirectBundle\Entity\Repository\SlugRepository;
use Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures\LoadVideosTestData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @dbIsolationPerTest
 */
class VideosControllerTest extends FrontendControllerTestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([LoadVideosTestData::class]);
    }

    public function testListActionReturnsSuccessfulResponse(): void
    {
        $slug = self::getContainer()->get(SlugRepository::class)->findOneBy([
            'routeName' => 'oro_resource_library_videos_list'
        ]);

        $this->client->request(Request::METHOD_GET, $slug->getUrl());
        $response = $this->client->getResponse();

        self::assertResponseStatusCodeEquals($response, Response::HTTP_OK);
    }
}
