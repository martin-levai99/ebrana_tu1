<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220711060902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE subject subject VARCHAR(255) NOT NULL, CHANGE content content VARCHAR(4000) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE thumbnail thumbnail VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE subject subject VARCHAR(255) DEFAULT NULL, CHANGE content content VARCHAR(4000) DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE thumbnail thumbnail VARCHAR(255) NOT NULL');
    }
}
