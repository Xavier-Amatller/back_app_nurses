<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422142528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diagnosticos (id INT AUTO_INCREMENT NOT NULL, dia_diagnostico LONGTEXT DEFAULT NULL, dia_motivo LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registros ADD dia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDFAC1F7597 FOREIGN KEY (dia_id) REFERENCES diagnosticos (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E78E3BDFAC1F7597 ON registros (dia_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDFAC1F7597');
        $this->addSql('DROP TABLE diagnosticos');
        $this->addSql('DROP INDEX UNIQ_E78E3BDFAC1F7597 ON registros');
        $this->addSql('ALTER TABLE registros DROP dia_id');
    }
}
