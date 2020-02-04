<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200204090030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE location location VARCHAR(100) NOT NULL, CHANGE experience experience enum(\'Tous\', \'Junior (0 à 2 ans)\', \'Confirmé (3 à 6 ans)\', \'Senior (7 ans et plus)\'), CHANGE salary salary INT DEFAULT NULL, CHANGE type type enum(\'CDI\', \'CDD\', \'Stage\')');
        $this->addSql('ALTER TABLE business CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE name name VARCHAR(100) DEFAULT NULL, CHANGE employees_number employees_number enum(\'20 employés et moins\', \'21 à 100 employés\', \'101 à 500 employés\', \'Plus de 500 employés\'), CHANGE activity_area activity_area VARCHAR(255) DEFAULT NULL, CHANGE location location VARCHAR(100) DEFAULT NULL, CHANGE kind kind enum(\'Cabinet de recrutement\', \'Editeur de logiciel\', \'Entreprise\', \'ESN / Cabinet de conseil\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE business CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE name name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE employees_number employees_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE activity_area activity_area VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE location location VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE kind kind VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE offer CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE location location LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE experience experience VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE salary salary INT DEFAULT NULL, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
