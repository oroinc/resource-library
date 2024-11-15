<?php

namespace Oro\Bundle\ResourceLibraryBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteFileCollectionContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\LiteratureApplicationNoteFile;
use Oro\Bundle\TestFrameworkBundle\Tests\Functional\DataFixtures\LoadUser;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;

class LoadLiteratureApplicationNoteTestData extends AbstractLoadWebCatalogTestData implements DependentFixtureInterface
{
    use LoadTestFileTrait;

    #[\Override]
    public function getDependencies(): array
    {
        return [
            LoadUser::class,
            LoadResourceLibraryTestData::class
        ];
    }

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $webCatalog = $this->getReference(LoadWebCatalogTestData::WEB_CATALOG_REFERENCE_NAME);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/literature_application_note_data.yml'
            ),
            $this->getReference(LoadResourceLibraryTestData::RESOURCE_LIBRARY_NODE_REFERENCE_NAME)
        );

        $this->generateCache($webCatalog);
    }

    #[\Override]
    protected function getContentVariant(ObjectManager $manager, string $type, array $params): ContentVariant
    {
        $variant = new ContentVariant();
        $variant->setType($type);

        switch ($variant->getType()) {
            case LiteratureApplicationNoteContentVariantType::TYPE:
                $variant->setDescription($params['description']);
                break;

            case LiteratureApplicationNoteFileCollectionContentVariantType::TYPE:
                foreach ($params['files'] as $file) {
                    $note = new LiteratureApplicationNoteFile();
                    $note->setFile(
                        $this->createFileFile(
                            $manager,
                            $this->getReference(LoadUser::USER),
                            $this->getFileLocator()->locate($file['file']),
                            $file['title']
                        )
                    );
                    $variant->addLiteratureNoteFil($note);
                }
                break;
        }

        return $variant;
    }
}
