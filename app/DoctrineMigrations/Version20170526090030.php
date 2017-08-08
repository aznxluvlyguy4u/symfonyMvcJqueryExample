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
        $this->addSql("INSERT INTO membership_status (id, label, email_template_id, created_by_id, created_at, modified_at, is_deleted) VALUES
            (nextval('membership_status_id_seq'), 'First day scheduled', NULL, NULL, current_timestamp, current_timestamp, false), 
            (nextval('membership_status_id_seq'), 'First week evaluation', NULL, NULL, current_timestamp, current_timestamp, false), 
            (nextval('membership_status_id_seq'), 'Monthly evaluation', NULL, NULL, current_timestamp, current_timestamp, false), 
            (nextval('membership_status_id_seq'), 'Cancelled', NULL, NULL, current_timestamp, current_timestamp, false)
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
