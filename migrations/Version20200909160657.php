<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200909160657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, status INT NOT NULL, video_second INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, video_hash VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_chunk (id INT AUTO_INCREMENT NOT NULL, video_id INT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, status INT NOT NULL, INDEX IDX_1A64788C29C1004E (video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE video_chunk ADD CONSTRAINT FK_1A64788C29C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_chunk DROP FOREIGN KEY FK_1A64788C29C1004E');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE video_chunk');
    }
}
