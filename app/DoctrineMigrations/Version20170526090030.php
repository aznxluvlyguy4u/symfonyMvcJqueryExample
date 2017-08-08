<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Checkin initial MembershipStatus labels
 */
class Version20170526090030 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER SEQUENCE membership_status_id_seq RESTART WITH 1");
        $this->addSql("INSERT INTO membership_status (id, label) VALUES
            (nextval('membership_status_id_seq'), 'First day scheduled'), 
            (nextval('membership_status_id_seq'), 'First week evaluation'), 
            (nextval('membership_status_id_seq'), 'Monthly evaluation'), 
            (nextval('membership_status_id_seq'), 'Cancelled')
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM membership_status");
    }
}
