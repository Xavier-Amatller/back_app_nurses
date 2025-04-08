<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321151938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pacientes (id INT AUTO_INCREMENT NOT NULL, pac_num_historial INT NULL, pac_nombre VARCHAR(50) NULL, pac_apellidos VARCHAR(150) NULL, pac_fecha_nacimiento DATE NULL, pac_direccion_completa VARCHAR(255) NULL, pac_lengua_materna VARCHAR(45) NULL, pac_antecedentes LONGTEXT NULL, pac_alergias LONGTEXT NULL, pac_nombre_cuidador VARCHAR(150) NULL, pac_telefono_cuidador VARCHAR(9) NULL, pac_fecha_ingreso DATETIME NULL, pac_timestamp DATETIME NULL, UNIQUE INDEX UNIQ_971B7851BCCDF26A (pac_num_historial), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E87D4B909545DD69 ON auxiliares (aux_num_trabajador)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pacientes');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX UNIQ_E87D4B909545DD69 ON auxiliares');
    }
}
