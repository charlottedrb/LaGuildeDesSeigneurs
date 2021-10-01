<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211001122408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE characters (
            id TINYINT UNSIGNED AUTO_INCREMENT NOT NULL, 
            name VARCHAR(16) NOT NULL, 
            surname VARCHAR(64) NOT NULL, 
            caste VARCHAR(16) DEFAULT NULL, 
            knowledge VARCHAR(255) DEFAULT NULL, 
            intelligence TINYINT UNSIGNED DEFAULT NULL, 
            life TINYINT UNSIGNED DEFAULT NULL, 
            image VARCHAR(128) DEFAULT NULL, 
            kind VARCHAR(16) NOT NULL, 
            creation DATETIME NOT NULL, 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE characters');
    }
}
