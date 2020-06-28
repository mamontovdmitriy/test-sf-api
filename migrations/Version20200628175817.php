<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200628175817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT NOT NULL, article_id INT DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10C7294869C ON media (article_id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_23a0e667e9e4c8c');
        $this->addSql('ALTER TABLE article DROP photo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE media');
        $this->addSql('ALTER TABLE article ADD photo_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_23a0e667e9e4c8c ON article (photo_id)');
    }
}
