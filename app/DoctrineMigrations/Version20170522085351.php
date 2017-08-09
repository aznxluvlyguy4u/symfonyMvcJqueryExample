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
        $this->addSql("INSERT INTO company_status (id, label, position, email_template_id, created_by_id, created_at, modified_at, is_deleted) VALUES
        (nextval('company_status_id_seq'), 'Suspect', 0, NULL, NULL, current_timestamp, current_timestamp, false), 
        (nextval('company_status_id_seq'), 'Lead', 1, NULL, NULL, current_timestamp, current_timestamp, false), 
        (nextval('company_status_id_seq'), 'First contact', 2, NULL, NULL, current_timestamp, current_timestamp, false), 
        (nextval('company_status_id_seq'), 'Appointment', 3, NULL, NULL, current_timestamp, current_timestamp, false), 
        (nextval('company_status_id_seq'), 'Follow up', 4, NULL, NULL, current_timestamp,current_timestamp, false), 
        (nextval('company_status_id_seq'), 'Client waiting for contract', 5, NULL, NULL, current_timestamp, current_timestamp, false), 
        (nextval('company_status_id_seq'), 'Waiting for contract to be signed', 6, NULL, NULL, current_timestamp, current_timestamp, false), 
        (nextval('company_status_id_seq'), 'Contract signed', 7, NULL, NULL, current_timestamp, current_timestamp, false)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM company_status");
    }
}
