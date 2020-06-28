<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200627172942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO status (id, name) VALUES
(1, 'DRAFT'),
(2, 'REVIEW'),
(3, 'PUBLISH')
");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM status WHERE id IN (1,2,3)');
    }
}
