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
        $this->addSql("INSERT INTO company_sector (id, label) VALUES
            (nextval('company_sector_id_seq'), 'Cultuur, sport en recreatie'), 
            (nextval('company_sector_id_seq'), 'Landbouw'), 
            (nextval('company_sector_id_seq'), 'Industrie'), 
            (nextval('company_sector_id_seq'), 'Financiele instellingen'), 
            (nextval('company_sector_id_seq'), 'Informatie en communcatie'), 
            (nextval('company_sector_id_seq'), 'Groot- en detailhandel'), 
            (nextval('company_sector_id_seq'), 'Onderwijs'), 
            (nextval('company_sector_id_seq'), 'Gezondheids- en welzijnszorg'),
            (nextval('company_sector_id_seq'), 'Vervoer en opslag'),
            (nextval('company_sector_id_seq'), 'Onroerend goed'), 
            (nextval('company_sector_id_seq'), 'Anders')
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
