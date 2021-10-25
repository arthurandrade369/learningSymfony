<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025165048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, email VARCHAR(96) NOT NULL, scope VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, type VARCHAR(32) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CAC89EACE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, publisher_id INT NOT NULL, title VARCHAR(64) NOT NULL, author VARCHAR(64) NOT NULL, quantity_pages INT NOT NULL, release_date VARCHAR(4) NOT NULL, INDEX IDX_4A1B2A9240C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_access_token (id INT AUTO_INCREMENT NOT NULL, refresh_token_id INT DEFAULT NULL, access_token VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, expiration_at DATETIME NOT NULL, token_type VARCHAR(32) NOT NULL, address VARCHAR(12) NOT NULL, UNIQUE INDEX UNIQ_454D9673F765F60E (refresh_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_refresh_token (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, refresh_token VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_4DD907329B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A9240C86FCE FOREIGN KEY (publisher_id) REFERENCES publishers (id)');
        $this->addSql('ALTER TABLE oauth2_access_token ADD CONSTRAINT FK_454D9673F765F60E FOREIGN KEY (refresh_token_id) REFERENCES oauth2_refresh_token (id)');
        $this->addSql('ALTER TABLE oauth2_refresh_token ADD CONSTRAINT FK_4DD907329B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
        $this->addSql('INSERT INTO accounts (name, email, scope, password, type, enabled, created_at, modified_at) VALUES ("admin", "admin@admin.com", "ROLE_ADMIN", "21232f297a57a5a743894a0e4a801fc3", "admin", 1, NOW(), NOW())');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth2_refresh_token DROP FOREIGN KEY FK_4DD907329B6B5FBA');
        $this->addSql('ALTER TABLE oauth2_access_token DROP FOREIGN KEY FK_454D9673F765F60E');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9240C86FCE');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE oauth2_access_token');
        $this->addSql('DROP TABLE oauth2_refresh_token');
        $this->addSql('DROP TABLE publishers');
    }
}
