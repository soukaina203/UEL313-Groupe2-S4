<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222211107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE keyword (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A93713B5E237E06 ON keyword (name)');
        $this->addSql('CREATE TABLE link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, keyword_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_36AC99F1115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_36AC99F1115D4552 ON link (keyword_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE keyword');
        $this->addSql('DROP TABLE link');
    }
}
