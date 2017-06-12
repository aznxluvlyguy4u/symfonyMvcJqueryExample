<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170522125428 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER SEQUENCE company_sector_id_seq RESTART WITH 1");
        $this->addSql("INSERT INTO company_sector (id, label, created_by_id, created_at, modified_at, is_deleted) VALUES
            (nextval('company_sector_id_seq'), 'Cultuur, sport en recreatie', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Landbouw', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Industrie', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Financiele instellingen', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Informatie en communcatie', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Groot- en detailhandel', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Onderwijs', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Gezondheids- en welzijnszorg', NULL, current_timestamp, current_timestamp, false),
            (nextval('company_sector_id_seq'), 'Vervoer en opslag', NULL, current_timestamp, current_timestamp, false),
            (nextval('company_sector_id_seq'), 'Onroerend goed', NULL, current_timestamp, current_timestamp, false), 
            (nextval('company_sector_id_seq'), 'Anders', NULL, current_timestamp, current_timestamp, false)
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM company_sector");
    }
}
