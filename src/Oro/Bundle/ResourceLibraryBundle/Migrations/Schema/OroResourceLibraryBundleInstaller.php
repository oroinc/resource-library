<?php

namespace Oro\Bundle\ResourceLibraryBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareTrait;
use Oro\Bundle\EntityBundle\EntityConfig\DatagridScope;
use Oro\Bundle\EntityConfigBundle\Entity\ConfigModel;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\ExtendOptionsManager;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareTrait;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroResourceLibraryBundleInstaller implements
    Installation,
    ExtendExtensionAwareInterface,
    AttachmentExtensionAwareInterface
{
    use ExtendExtensionAwareTrait;
    use AttachmentExtensionAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function getMigrationVersion(): string
    {
        return 'v1_0';
    }

    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        $this->createLiteratureApplicationNoteTable($schema);
        $this->createVideoTable($schema);
        $this->createMediaKitTable($schema);
        $this->createNewsAnnouncementsTable($schema);
        $this->addLiteratureExtension($schema);

        $table = $schema->getTable('oro_web_catalog_variant');

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
                'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_FALSE],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false],
                'merge' => ['display' => false],
                'dataaudit' => ['auditable' => false]
            ]
        );

        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'news_announcements_article',
            'oro_news_announce_article',
            'id',
            [
                ExtendOptionsManager::MODE_OPTION => ConfigModel::MODE_READONLY,
                'entity' => ['label' => 'oro.resourcelibrary.newsannouncementsarticle.entity_label'],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'on_delete' => 'CASCADE',
                ],
                'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_FALSE],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false],
                'merge' => ['display' => false],
            ]
        );

        $this->addFileRelation($schema);

        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'media_kit',
            'oro_rl_media_kit',
            'id',
            [
                ExtendOptionsManager::MODE_OPTION => ConfigModel::MODE_READONLY,
                'entity' => ['label' => 'oro.resourcelibrary.mediakit.entity_label'],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist'],
                    'on_delete' => 'CASCADE',
                ],
                'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_FALSE],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false],
                'merge' => ['display' => false],
                'dataaudit' => ['auditable' => false]
            ]
        );
    }

    private function addLiteratureExtension(Schema $schema): void
    {
        $table = $schema->getTable('oro_web_catalog_variant');
        $table->addColumn(
            'description',
            'text',
            [
                OroOptions::KEY => [
                    ExtendOptionsManager::MODE_OPTION => ConfigModel::MODE_READONLY,
                    'extend' => ['is_extend' => true, 'owner' => ExtendScope::OWNER_CUSTOM],
                    'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_FALSE],
                    'form' => ['is_enabled' => false],
                    'view' => ['is_displayable' => false],
                    'merge' => ['display' => false],
                    'dataaudit' => ['auditable' => false],
                ],
            ]
        );

        //Adds "content_variant" relation field to "oro_literature_note_file" table
        $this->extendExtension->addManyToOneRelation(
            $schema,
            'oro_literature_note_file',
            'content_variant',
            $table,
            'id',
            [
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'on_delete' => 'CASCADE',
                ],
                'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_FALSE],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false],
                'merge' => ['display' => false],
            ]
        );

        $this->extendExtension->addManyToOneInverseRelation(
            $schema,
            'oro_literature_note_file',
            'content_variant',
            $table,
            'literature_note_files',
            ['id'],
            ['id'],
            ['id'],
            [
                ExtendOptionsManager::MODE_OPTION => ConfigModel::MODE_READONLY,
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'without_default' => true,
                    'fetch' => 'extra_lazy',
                    'on_delete' => 'CASCADE',
                ],
                'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_FALSE],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false],
                'merge' => ['display' => false],
                'importexport' => ['excluded' => true],
            ]
        );
    }

    private function createLiteratureApplicationNoteTable(Schema $schema): void
    {
        $table = $schema->createTable('oro_literature_note_file');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);

        $this->attachmentExtension->addFileRelation(
            $schema,
            'oro_literature_note_file',
            'file',
            [
                'attachment' => ['acl_protected' => false, 'use_dam' => true],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'without_default' => true,
                    'on_delete' => 'SET NULL',
                ],
            ],
            10
        );
    }

    private function addFileRelation(Schema $schema): void
    {
        $this->attachmentExtension->addFileRelation(
            $schema,
            'oro_web_catalog_variant',
            'pdf_file',
            [
                'attachment' => ['acl_protected' => false, 'use_dam' => true, 'mimetypes' => 'application/pdf'],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'without_default' => true,
                    'on_delete' => 'SET NULL',
                ],
            ],
            10
        );
    }

    private function createVideoTable(Schema $schema): void
    {
        $table = $schema->createTable('oro_rl_video');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('short_description', 'text', ['notnull' => true]);
        $table->addColumn('description', 'text', ['notnull' => true]);
        $table->addColumn('link', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime');
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['id']);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    private function createMediaKitTable(Schema $schema): void
    {
        $table = $schema->createTable('oro_rl_media_kit');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('description', 'text', ['notnull' => true]);
        $table->addColumn('link', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime');
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['id']);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );

        $this->attachmentExtension->addImageRelation(
            $schema,
            'oro_rl_media_kit',
            'banner',
            [
                'attachment' => ['acl_protected' => false, 'use_dam' => true],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'without_default' => true,
                    'on_delete' => 'SET NULL',
                ],
            ],
            10
        );

        $this->attachmentExtension->addFileRelation(
            $schema,
            'oro_rl_media_kit',
            'media_kit_file',
            [
                'attachment' => ['acl_protected' => false, 'use_dam' => true],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'on_delete' => 'SET NULL',
                ],
            ],
            10
        );

        $this->attachmentExtension->addFileRelation(
            $schema,
            'oro_rl_media_kit',
            'logo_package_file',
            [
                'attachment' => ['acl_protected' => false, 'use_dam' => true],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'on_delete' => 'SET NULL',
                ],
            ],
            10
        );
    }

    private function createNewsAnnouncementsTable(Schema $schema): void
    {
        $table = $schema->createTable('oro_news_announce_article');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('description', 'text');
        $table->addColumn('short_description', 'text');
        $table->addColumn('created_at', 'datetime');
        $table->setPrimaryKey(['id']);

        $this->attachmentExtension->addImageRelation(
            $schema,
            'oro_news_announce_article',
            'image',
            [
                'attachment' => ['acl_protected' => false, 'use_dam' => true],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['persist', 'remove'],
                    'without_default' => true,
                    'on_delete' => 'SET NULL',
                ],
            ]
        );
    }
}
