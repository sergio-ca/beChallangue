<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170221145139 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE negative_words (id INTEGER NOT NULL, word VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEE5BCA7C3F17511 ON negative_words (word)');
        $this->addSql('CREATE TABLE positive_words (id INTEGER NOT NULL, word VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C4E84ABC3F17511 ON positive_words (word)');
        $this->addSql('CREATE TABLE reviews (id INTEGER NOT NULL, text CLOB NOT NULL, matches CLOB DEFAULT NULL, score INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE topics (id INTEGER NOT NULL, word VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_91F64639C3F17511 ON topics (word)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE negative_words');
        $this->addSql('DROP TABLE positive_words');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE topics');
    }
}
