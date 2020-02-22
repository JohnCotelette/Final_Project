<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200217110633 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL, ADD phone_number VARCHAR(20) DEFAULT NULL, ADD web_site VARCHAR(180) DEFAULT NULL, CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE cv_id cv_id INT DEFAULT NULL, CHANGE business_id business_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password_token password_token VARCHAR(100) DEFAULT NULL, CHANGE public public TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE user DROP created_at, DROP phone_number, DROP web_site, CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE cv_id cv_id INT DEFAULT NULL, CHANGE business_id business_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password_token password_token VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE public public TINYINT(1) DEFAULT \'NULL\'');
    }
}
