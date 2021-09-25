<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210925114104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE refresh_token 
            (id INT AUTO_INCREMENT NOT NULL,
            refresh_token VARCHAR(64) NOT NULL,
            created_at DATETIME NOT NULL,
            modified_at DATETIME NOT NULL,
            expiration_at DATETIME NOT NULL,
            account INT NOT NULL,
            PRIMARY KEY(id))
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE acess_token 
            (id INT AUTO_INCREMENT NOT NULL,
            acess_token VARCHAR(64) NOT NULL,
            created_at DATETIME NOT NULL,
            modified_at DATETIME NOT NULL,
            expiration_at DATETIME NOT NULL,
            type_token VARCHAR(32) NOT NULL,
            refresh_token INT NOT NULL,
            PRIMARY KEY(id)) 
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE acess_token');
        $this->addSql('DROP TABLE refresh_token');
    }
}
