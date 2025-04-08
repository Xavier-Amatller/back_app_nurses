<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328162117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE habitaciones (id INT AUTO_INCREMENT NOT NULL, pac_num_historial INT DEFAULT NULL, hab_id VARCHAR(5) NOT NULL, hab_obs VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E10783BBCCDF26A (pac_num_historial), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE habitaciones ADD CONSTRAINT FK_E10783BBCCDF26A FOREIGN KEY (pac_num_historial) REFERENCES pacientes (pac_num_historial)'); 
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habitaciones DROP FOREIGN KEY FK_E10783BBCCDF26A');
        $this->addSql('DROP TABLE habitaciones');
    }
}
