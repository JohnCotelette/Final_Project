<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123155839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, field_id INT NOT NULL, name VARCHAR(80) NOT NULL, INDEX IDX_64C19C1443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, color VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_category (offer_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_7F31A9A353C674EE (offer_id), INDEX IDX_7F31A9A312469DE2 (category_id), PRIMARY KEY(offer_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE offer_category ADD CONSTRAINT FK_7F31A9A353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_category ADD CONSTRAINT FK_7F31A9A312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE cv_id cv_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password_token password_token VARCHAR(100) DEFAULT NULL, CHANGE business business VARCHAR(70) DEFAULT NULL');
        $this->addSql('ALTER TABLE avatar CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE cv CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE offer CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE experience experience enum(\'Tous\', \'Junior (0 à 2 ans)\', \'Confirmé (3 à 6 ans)\', \'Senior (7 ans et plus)\'), CHANGE salary salary INT DEFAULT NULL, CHANGE type type enum(\'CDI\', \'CDD\', \'Stage\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE offer_category DROP FOREIGN KEY FK_7F31A9A312469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1443707B0');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE offer_category');
        $this->addSql('ALTER TABLE avatar CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cv CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE offer CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE experience experience VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE salary salary INT DEFAULT NULL, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE cv_id cv_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password_token password_token VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE business business VARCHAR(70) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
