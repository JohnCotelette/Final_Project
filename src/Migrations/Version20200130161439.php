<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200130161439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE business (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, siret_number BIGINT NOT NULL, name VARCHAR(100) DEFAULT NULL, employees_number enum(\'20 employés et moins\', \'21 à 100 employés\', \'101 à 500 employés\', \'Plus de 500 employés\'), activity_area VARCHAR(255) DEFAULT NULL, location VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_8D36E3886383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E3886383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
        $this->addSql('ALTER TABLE avatar CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE cv CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE offer CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE experience experience enum(\'Tous\', \'Junior (0 à 2 ans)\', \'Confirmé (3 à 6 ans)\', \'Senior (7 ans et plus)\'), CHANGE salary salary INT DEFAULT NULL, CHANGE type type enum(\'CDI\', \'CDD\', \'Stage\')');
        $this->addSql('ALTER TABLE user ADD business_id INT DEFAULT NULL, DROP business, CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE cv_id cv_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password_token password_token VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A89DB457 ON user (business_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A89DB457');
        $this->addSql('DROP TABLE business');
        $this->addSql('ALTER TABLE avatar CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cv CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE offer CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE experience experience VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE salary salary INT DEFAULT NULL, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_8D93D649A89DB457 ON user');
        $this->addSql('ALTER TABLE user ADD business VARCHAR(70) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, DROP business_id, CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE cv_id cv_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password_token password_token VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
