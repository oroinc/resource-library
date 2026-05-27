<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\ContentVariantType\LiteratureApplicationNoteFileCollectionContentVariantType;
use Oro\Bundle\ResourceLibraryBundle\Entity\LiteratureApplicationNoteFile;
use Oro\Bundle\WebCatalogBundle\Entity\ContentVariant;
use Oro\Bundle\WebCatalogBundle\Entity\WebCatalog;
use Oro\Bundle\WebCatalogBundle\Migrations\Data\Demo\ORM\AbstractLoadWebCatalogDemoData;

/**
 * Literature & Application Notes demo data
 */
class LoadLiteratureApplicationNotesDemoData extends AbstractLoadWebCatalogDemoData implements DependentFixtureInterface
{
    use LoadDemoFileTrait;

    /** @var ObjectManager */
    private $manager;

    #[\Override]
    public function getDependencies()
    {
        return [
            ResourceLibraryDemoData::class,
        ];
    }

    #[\Override]
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $webCatalog = $this->getWebCatalog($manager);

        $this->loadContentNodes(
            $manager,
            $webCatalog,
            $this->getWebCatalogData(
                '@OroResourceLibraryBundle/Migrations/Data/Demo/ORM/data/literature_application_note_data.yml'
            ),
            ResourceLibraryDemoData::getResourceLibraryNode($manager)
        );

        $manager->flush();

        $this->generateCache($webCatalog);
    }

    /**
     * @param ObjectManager $manager
     * @return WebCatalog
     */
    private function getWebCatalog(ObjectManager $manager)
    {
        return $manager->getRepository(WebCatalog::class)
            ->findOneBy(['name' => self::DEFAULT_WEB_CATALOG_NAME]);
    }

    #[\Override]
    protected function getContentVariant($type, array $params)
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
                            $this->manager,
                            $this->getFileLocator()->locate($file['file']),
                            $file['title']
                        )
                    );
                    $variant->addLiteratureNoteFile($note);
                }
                break;
        }

        return $variant;
    }
}
