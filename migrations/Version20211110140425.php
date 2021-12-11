<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110140425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_settings (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, profile_image VARCHAR(255) DEFAULT NULL, presentation VARCHAR(255) DEFAULT NULL, likes VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD tmp1_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D196454EB FOREIGN KEY (tmp1_id) REFERENCES user_settings (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D196454EB ON post (tmp1_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D196454EB');
        $this->addSql('DROP TABLE user_settings');
        $this->addSql('DROP INDEX IDX_5A8A6C8D196454EB ON post');
        $this->addSql('ALTER TABLE post DROP tmp1_id');
    }
}
