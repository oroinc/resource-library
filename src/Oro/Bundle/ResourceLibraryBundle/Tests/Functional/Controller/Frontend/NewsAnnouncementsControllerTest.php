<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\RedirectBundle\Entity\Repository\SlugRepository;
use Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures\LoadNewsAnnouncementsTestData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @dbIsolationPerTest
 */
class NewsAnnouncementsControllerTest extends FrontendControllerTestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([LoadNewsAnnouncementsTestData::class]);
    }

    public function testIndexActionReturnsSuccessfulResponse(): void
    {
        $slug = self::getContainer()->get(SlugRepository::class)->findOneBy([
            'routeName' => 'oro_resource_library_news_announcements_index'
        ]);

        $this->client->request(Request::METHOD_GET, $slug->getUrl());
        $response = $this->client->getResponse();

        self::assertResponseStatusCodeEquals($response, Response::HTTP_OK);
    }

    public function testArticleActionReturnsSuccessfulResponse(): void
    {
        $slug = self::getContainer()->get(SlugRepository::class)->findOneBy([
            'routeName' => 'oro_resource_library_news_announcements_article'
        ]);

        $this->client->request(Request::METHOD_GET, $slug->getUrl());
        $response = $this->client->getResponse();

        self::assertResponseStatusCodeEquals($response, Response::HTTP_OK);
    }
}
