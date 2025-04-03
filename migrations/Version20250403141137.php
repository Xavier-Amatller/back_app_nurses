<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403141137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registro ADD pac_num_historial_id INT NOT NULL, ADD cv_id_id INT DEFAULT NULL, ADD reg_timestamp DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B60F73B68 FOREIGN KEY (pac_num_historial_id) REFERENCES pacientes (id)');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85BF0404E48 FOREIGN KEY (cv_id_id) REFERENCES constantes_vitales (id)');
        $this->addSql('CREATE INDEX IDX_397CA85B60F73B68 ON registro (pac_num_historial_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_397CA85BF0404E48 ON registro (cv_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registro DROP FOREIGN KEY FK_397CA85B60F73B68');
        $this->addSql('ALTER TABLE registro DROP FOREIGN KEY FK_397CA85BF0404E48');
        $this->addSql('DROP INDEX IDX_397CA85B60F73B68 ON registro');
        $this->addSql('DROP INDEX UNIQ_397CA85BF0404E48 ON registro');
        $this->addSql('ALTER TABLE registro DROP pac_num_historial_id, DROP cv_id_id, DROP reg_timestamp');
    }
}
