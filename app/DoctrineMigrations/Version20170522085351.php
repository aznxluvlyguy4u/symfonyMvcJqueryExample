<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170522085351 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER SEQUENCE company_status_id_seq RESTART WITH 1");
        $this->addSql("INSERT INTO company_status (id, label) VALUES
        (nextval('company_status_id_seq'), 'Suspect'), 
        (nextval('company_status_id_seq'), 'Lead'), 
        (nextval('company_status_id_seq'), 'First contact'), 
        (nextval('company_status_id_seq'), 'Appointment'), 
        (nextval('company_status_id_seq'), 'Follow up'), 
        (nextval('company_status_id_seq'), 'Client waiting for contract'), 
        (nextval('company_status_id_seq'), 'Waiting for contract to be signed'), 
        (nextval('company_status_id_seq'), 'Contract signed')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM company_status");
    }
}
