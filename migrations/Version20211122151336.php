<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211122151336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD gls_name VARCHAR(16) NOT NULL, ADD gls_intelligence INT DEFAULT NULL, ADD gls_life INT DEFAULT NULL, ADD gls_kind VARCHAR(16) NOT NULL, ADD gls_creation DATETIME NOT NULL, ADD gls_modification DATETIME NOT NULL, DROP name, DROP intelligence, DROP life, DROP kind, DROP creation, DROP modification, CHANGE surname gls_surname VARCHAR(64) NOT NULL, CHANGE caste gls_caste VARCHAR(16) DEFAULT NULL, CHANGE knowledge gls_knowledge VARCHAR(255) DEFAULT NULL, CHANGE image gls_image VARCHAR(128) DEFAULT NULL, CHANGE identifier gls_identifier VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE players ADD gls_firstname VARCHAR(255) NOT NULL, ADD gls_lastname VARCHAR(255) NOT NULL, ADD gls_email VARCHAR(255) NOT NULL, ADD gls_identifier VARCHAR(40) NOT NULL, ADD gls_pseudo VARCHAR(40) NOT NULL, ADD gls_creation DATETIME NOT NULL, ADD gls_modification DATETIME NOT NULL, DROP firstname, DROP lastname, DROP email, DROP identifier, DROP pseudo, DROP creation, DROP modification, CHANGE mirian gls_mirian INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD name VARCHAR(16) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD intelligence INT DEFAULT NULL, ADD life INT DEFAULT NULL, ADD kind VARCHAR(16) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD creation DATETIME NOT NULL, ADD modification DATETIME NOT NULL, DROP gls_name, DROP gls_intelligence, DROP gls_life, DROP gls_kind, DROP gls_creation, DROP gls_modification, CHANGE gls_surname surname VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE gls_caste caste VARCHAR(16) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE gls_knowledge knowledge VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE gls_image image VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE gls_identifier identifier VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE players ADD firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD lastname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD identifier VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD pseudo VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD creation DATETIME NOT NULL, ADD modification DATETIME NOT NULL, DROP gls_firstname, DROP gls_lastname, DROP gls_email, DROP gls_identifier, DROP gls_pseudo, DROP gls_creation, DROP gls_modification, CHANGE gls_mirian mirian INT NOT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
