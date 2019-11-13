<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityConfigBundle\Entity\ConfigModel;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\ExtendOptionsManager;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroResourceLibraryBundleInstaller implements
    Installation,
    ExtendExtensionAwareInterface
{
    /** @var ExtendExtension */
    private $extendExtension;

    /**
     * {@inheritdoc}
     */
    public function setExtendExtension(ExtendExtension $extendExtension): void
    {
        $this->extendExtension = $extendExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion(): string
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        $this->createVideoTable($schema);

        $table = $schema->getTable('oro_web_catalog_variant');
        $table->addColumn(
            'description',
            'text',
            [
                OroOptions::KEY => [
                    ExtendOptionsManager::MODE_OPTION => ConfigModel::MODE_READONLY,
                    'extend' => ['is_extend' => true, 'owner' => ExtendScope::OWNER_CUSTOM],
                    'datagrid' => ['is_visible' => false],
                    'form' => ['is_enabled' => false],
                    'view' => ['is_displayable' => false],
                    'merge' => ['display' => false],
                    'dataaudit' => ['auditable' => false],
                ],
            ]
        );
        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'video',
            'oro_rl_video',
            'id',
            [
                ExtendOptionsManager::MODE_OPTION => ConfigModel::MODE_READONLY,
                'entity' => ['label' => 'oro.resourcelibrary.video.entity_label'],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist'],
                    'on_delete' => 'CASCADE',
                ],
                'datagrid' => ['is_visible' => false],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false],
                'merge' => ['display' => false],
                'dataaudit' => ['auditable' => false]
            ]
        );
    }

    /**
     * @param Schema $schema
     */
    private function createVideoTable(Schema $schema): void
    {
        $table = $schema->createTable('oro_rl_video');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('short_description', 'text', ['notnull' => true]);
        $table->addColumn('description', 'text', ['notnull' => true]);
        $table->addColumn('link', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['id']);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }
}
