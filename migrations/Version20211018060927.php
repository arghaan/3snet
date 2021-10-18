<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018060927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE complaint (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, ip VARCHAR(255) NOT NULL, INDEX IDX_5F2732B54B89032C (post_id), INDEX ip_idx (ip), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, alias VARCHAR(255) DEFAULT NULL, FULLTEXT INDEX search_idx (text), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, ip VARCHAR(255) NOT NULL, rating INT NOT NULL, INDEX IDX_D88926224B89032C (post_id), INDEX ip_idx (ip), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B54B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926224B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE complaint DROP FOREIGN KEY FK_5F2732B54B89032C');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926224B89032C');
        $this->addSql('DROP TABLE complaint');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE rating');
    }
}
