<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411150055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movilizaciones (id INT AUTO_INCREMENT NOT NULL, mov_sedestacion TINYINT(1) NOT NULL, mov_ajuda_deambulacion TINYINT(1) NOT NULL, mov_ajuda_descripcion VARCHAR(255) DEFAULT NULL, mov_cambios VARCHAR(255) DEFAULT NULL, mov_decubitos VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registros ADD mov_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDFC99EBED9 FOREIGN KEY (mov_id) REFERENCES movilizaciones (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E78E3BDFC99EBED9 ON registros (mov_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDFC99EBED9');
        $this->addSql('DROP TABLE movilizaciones');
        $this->addSql('DROP INDEX UNIQ_E78E3BDFC99EBED9 ON registros');
        $this->addSql('ALTER TABLE registros DROP mov_id');
    }
}
