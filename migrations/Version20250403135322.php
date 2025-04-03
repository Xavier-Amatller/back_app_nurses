<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403135322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE constantes_vitales (id INT AUTO_INCREMENT NOT NULL, cv_ta_sistolica NUMERIC(10, 0) NOT NULL, cv_ta_diastolica NUMERIC(10, 0) NOT NULL, cv_frequencia_respiratoria NUMERIC(10, 0) NOT NULL, cv_pulso NUMERIC(10, 0) NOT NULL, cv_temperatura NUMERIC(10, 0) NOT NULL, cv_saturacion_oxigeno NUMERIC(10, 0) DEFAULT NULL, cv_talla NUMERIC(10, 0) DEFAULT NULL, cv_diuresis NUMERIC(10, 0) DEFAULT NULL, cv_deposiciones VARCHAR(45) DEFAULT NULL, cv_stp NUMERIC(10, 0) DEFAULT NULL, cv_timestamp DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registro (id INT AUTO_INCREMENT NOT NULL, aux_num_trabajador_id INT NOT NULL, INDEX IDX_397CA85B13673B47 (aux_num_trabajador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B13673B47 FOREIGN KEY (aux_num_trabajador_id) REFERENCES auxiliares (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registro DROP FOREIGN KEY FK_397CA85B13673B47');
        $this->addSql('DROP TABLE constantes_vitales');
        $this->addSql('DROP TABLE registro');
    }
}
