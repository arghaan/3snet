<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015061158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE complaint DROP FOREIGN KEY FK_5F2732B5E85F12B8');
        $this->addSql('DROP INDEX IDX_5F2732B5E85F12B8 ON complaint');
        $this->addSql('ALTER TABLE complaint CHANGE post_id_id post_id INT NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B54B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_5F2732B54B89032C ON complaint (post_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE complaint DROP FOREIGN KEY FK_5F2732B54B89032C');
        $this->addSql('DROP INDEX IDX_5F2732B54B89032C ON complaint');
        $this->addSql('ALTER TABLE complaint CHANGE post_id post_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5E85F12B8 FOREIGN KEY (post_id_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_5F2732B5E85F12B8 ON complaint (post_id_id)');
    }
}
