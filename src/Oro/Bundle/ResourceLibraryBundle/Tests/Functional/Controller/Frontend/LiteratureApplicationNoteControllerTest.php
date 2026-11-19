<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\RedirectBundle\Entity\Repository\SlugRepository;
use Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures\LoadLiteratureApplicationNoteTestData;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @dbIsolationPerTest
 */
class LiteratureApplicationNoteControllerTest extends FrontendControllerTestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([LoadLiteratureApplicationNoteTestData::class]);
    }

    public function testIndexActionReturnsSuccessfulResponse(): void
    {
        $slug = self::getContainer()->get(SlugRepository::class)->findOneBy([
            'routeName' => 'oro_resource_library_literature_application_note_index'
        ]);

        $this->client->request(Request::METHOD_GET, $slug->getUrl());
        $response = $this->client->getResponse();

        self::assertResponseStatusCodeEquals($response, Response::HTTP_OK);
    }

    public function testIndexActionNamesFileLinksByFileAndSectionTitles(): void
    {
        $slug = self::getContainer()->get(SlugRepository::class)->findOneBy([
            'routeName' => 'oro_resource_library_literature_application_note_index'
        ]);

        $crawler = $this->client->request(Request::METHOD_GET, $slug->getUrl());
        self::assertResponseStatusCodeEquals($this->client->getResponse(), Response::HTTP_OK);

        $fileLinks = $crawler->filter('.resource-library-literature__item a');
        $fileLinkAriaLabels = $fileLinks->each(static fn (Crawler $fileLink) => $fileLink->attr('aria-label'));
        $fileAndSectionTitles = $fileLinks->each(static fn (Crawler $fileLink) => sprintf(
            '%s, %s',
            $fileLink->text(),
            $fileLink->closest('.resource-library-literature__section')
                ->filter('.resource-library__section-title')
                ->text()
        ));

        self::assertSame($fileAndSectionTitles, $fileLinkAriaLabels);
        self::assertContains('220 Lumen Rechargeable Headlamp, English', $fileLinkAriaLabels);
        self::assertContains('220 Lumen Rechargeable Headlamp, French', $fileLinkAriaLabels);
        self::assertContains('220 Lumen Rechargeable Headlamp, German', $fileLinkAriaLabels);
    }
}
