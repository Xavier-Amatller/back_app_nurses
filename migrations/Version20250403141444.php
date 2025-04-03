<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403141444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registro DROP FOREIGN KEY FK_397CA85BF0404E48');
        $this->addSql('DROP INDEX UNIQ_397CA85BF0404E48 ON registro');
        $this->addSql('ALTER TABLE registro CHANGE cv_id_id cv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85BCFE419E2 FOREIGN KEY (cv_id) REFERENCES constantes_vitales (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_397CA85BCFE419E2 ON registro (cv_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registro DROP FOREIGN KEY FK_397CA85BCFE419E2');
        $this->addSql('DROP INDEX UNIQ_397CA85BCFE419E2 ON registro');
        $this->addSql('ALTER TABLE registro CHANGE cv_id cv_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85BF0404E48 FOREIGN KEY (cv_id_id) REFERENCES constantes_vitales (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_397CA85BF0404E48 ON registro (cv_id_id)');
    }
}
