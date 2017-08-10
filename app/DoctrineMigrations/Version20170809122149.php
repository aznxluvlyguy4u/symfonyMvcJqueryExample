<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170809122149 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addColumn( 'membership_status', 'position', 'integer' );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->removeColumn( 'membership_status', 'position' );
        // this down() migration is auto-generated, please modify it to your needs

    }
}
