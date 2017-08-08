<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170612214412 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE company_status SET created_at = current_timestamp, modified_at = current_timestamp, is_deleted = false WHERE created_at IS NULL AND modified_at IS NULL AND is_deleted IS NULL");
        $this->addSql("UPDATE company_sector SET created_at = current_timestamp, modified_at = current_timestamp, is_deleted = false WHERE created_at IS NULL AND modified_at IS NULL AND is_deleted IS NULL");
        $this->addSql("UPDATE membership_status SET created_at = current_timestamp, modified_at = current_timestamp, is_deleted = false WHERE created_at IS NULL AND modified_at IS NULL AND is_deleted IS NULL");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
