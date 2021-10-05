<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211005163003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE OAuth2_access_token (id INT AUTO_INCREMENT NOT NULL, access_token VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, expiration_at DATETIME NOT NULL, type_token VARCHAR(32) NOT NULL, refresh_token INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE OAuth2_refresh_token (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, expiration_at DATETIME NOT NULL, account INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('ALTER TABLE book CHANGE release_date release_date INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access_token (id INT AUTO_INCREMENT NOT NULL, access_token VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, expiration_at DATETIME NOT NULL, type_token VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, refresh_token INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, expiration_at DATETIME NOT NULL, account INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE OAuth2_access_token');
        $this->addSql('DROP TABLE OAuth2_refresh_token');
        $this->addSql('ALTER TABLE book CHANGE release_date release_date DATE NOT NULL');
    }
}
