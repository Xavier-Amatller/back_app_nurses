<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409133656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tipos_dieta (id INT AUTO_INCREMENT NOT NULL, tdie_desc VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipos_texturas (id INT AUTO_INCREMENT NOT NULL, ttext_desc VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dietas ADD die_ttext_id INT NOT NULL');
        $this->addSql('ALTER TABLE dietas ADD CONSTRAINT FK_5C6F440E3DC60D6A FOREIGN KEY (die_ttext_id) REFERENCES tipos_texturas (id)');
        $this->addSql('CREATE INDEX IDX_5C6F440E3DC60D6A ON dietas (die_ttext_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dietas DROP FOREIGN KEY FK_5C6F440E3DC60D6A');
        $this->addSql('DROP TABLE tipos_dieta');
        $this->addSql('DROP TABLE tipos_texturas');
        $this->addSql('DROP INDEX IDX_5C6F440E3DC60D6A ON dietas');
        $this->addSql('ALTER TABLE dietas DROP die_ttext_id');
    }
}
