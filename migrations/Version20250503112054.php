<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250503112054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auxiliares (id INT AUTO_INCREMENT NOT NULL, aux_num_trabajador VARCHAR(10) NOT NULL, aux_nombre VARCHAR(50) NOT NULL, aux_apellidos VARCHAR(150) NOT NULL, aux_password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E87D4B909545DD69 (aux_num_trabajador), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE constantes_vitales (id INT AUTO_INCREMENT NOT NULL, cv_ta_sistolica NUMERIC(10, 0) NOT NULL, cv_ta_diastolica NUMERIC(10, 0) NOT NULL, cv_frequencia_respiratoria NUMERIC(10, 0) NOT NULL, cv_pulso NUMERIC(10, 0) NOT NULL, cv_temperatura NUMERIC(10, 0) NOT NULL, cv_saturacion_oxigeno NUMERIC(10, 0) DEFAULT NULL, cv_talla NUMERIC(10, 0) DEFAULT NULL, cv_diuresis NUMERIC(10, 0) DEFAULT NULL, cv_deposiciones VARCHAR(45) DEFAULT NULL, cv_stp NUMERIC(10, 0) DEFAULT NULL, cv_timestamp DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diagnosticos (id INT AUTO_INCREMENT NOT NULL, dia_diagnostico LONGTEXT DEFAULT NULL, dia_motivo LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dietas (id INT AUTO_INCREMENT NOT NULL, die_ttext_id INT NOT NULL, die_autonomo TINYINT(1) NOT NULL, die_protesi TINYINT(1) NOT NULL, INDEX IDX_5C6F440E3DC60D6A (die_ttext_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dieta_tipos_dieta (dieta_id INT NOT NULL, tipos_dieta_id INT NOT NULL, INDEX IDX_9D9781DB615C2CBC (dieta_id), INDEX IDX_9D9781DBF89335CC (tipos_dieta_id), PRIMARY KEY(dieta_id, tipos_dieta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drenajes (id INT AUTO_INCREMENT NOT NULL, tdre_id INT NOT NULL, dre_debito LONGTEXT DEFAULT NULL, INDEX IDX_2313861BC4CEB6 (tdre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitaciones (id INT AUTO_INCREMENT NOT NULL, pac_id INT DEFAULT NULL, hab_id VARCHAR(5) NOT NULL, hab_obs VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E10783BAE21B650 (pac_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movilizaciones (id INT AUTO_INCREMENT NOT NULL, mov_sedestacion TINYINT(1) NOT NULL, mov_ajuda_deambulacion TINYINT(1) NOT NULL, mov_ajuda_descripcion VARCHAR(255) DEFAULT NULL, mov_cambios VARCHAR(255) DEFAULT NULL, mov_decubitos VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pacientes (id INT AUTO_INCREMENT NOT NULL, pac_num_historial INT NOT NULL, pac_nombre VARCHAR(50) NOT NULL, pac_apellidos VARCHAR(150) NOT NULL, pac_fecha_nacimiento DATE NOT NULL, pac_direccion_completa VARCHAR(255) NOT NULL, pac_lengua_materna VARCHAR(45) NOT NULL, pac_antecedentes LONGTEXT NOT NULL, pac_alergias LONGTEXT NOT NULL, pac_nombre_cuidador VARCHAR(150) NOT NULL, pac_telefono_cuidador VARCHAR(9) NOT NULL, pac_fecha_ingreso DATETIME NOT NULL, pac_timestamp DATETIME NOT NULL, UNIQUE INDEX UNIQ_971B785143914725 (pac_num_historial), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registros (id INT AUTO_INCREMENT NOT NULL, aux_id INT NOT NULL, pac_id INT NOT NULL, cv_id INT DEFAULT NULL, die_id INT DEFAULT NULL, mov_id INT DEFAULT NULL, dia_id INT DEFAULT NULL, dre_id INT DEFAULT NULL, reg_timestamp DATETIME DEFAULT NULL, INDEX IDX_E78E3BDF74D386B2 (aux_id), INDEX IDX_E78E3BDFAE21B650 (pac_id), UNIQUE INDEX UNIQ_E78E3BDFCFE419E2 (cv_id), UNIQUE INDEX UNIQ_E78E3BDF237DE2C0 (die_id), UNIQUE INDEX UNIQ_E78E3BDFC99EBED9 (mov_id), UNIQUE INDEX UNIQ_E78E3BDFAC1F7597 (dia_id), UNIQUE INDEX UNIQ_E78E3BDF344D4453 (dre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipos_dieta (id INT AUTO_INCREMENT NOT NULL, tdie_desc VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipos_drenajes (id INT AUTO_INCREMENT NOT NULL, tdre_desc VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipos_texturas (id INT AUTO_INCREMENT NOT NULL, ttext_desc VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dietas ADD CONSTRAINT FK_5C6F440E3DC60D6A FOREIGN KEY (die_ttext_id) REFERENCES tipos_texturas (id)');
        $this->addSql('ALTER TABLE dieta_tipos_dieta ADD CONSTRAINT FK_9D9781DB615C2CBC FOREIGN KEY (dieta_id) REFERENCES dietas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dieta_tipos_dieta ADD CONSTRAINT FK_9D9781DBF89335CC FOREIGN KEY (tipos_dieta_id) REFERENCES tipos_dieta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drenajes ADD CONSTRAINT FK_2313861BC4CEB6 FOREIGN KEY (tdre_id) REFERENCES tipos_drenajes (id)');
        $this->addSql('ALTER TABLE habitaciones ADD CONSTRAINT FK_E10783BAE21B650 FOREIGN KEY (pac_id) REFERENCES pacientes (id)');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDF74D386B2 FOREIGN KEY (aux_id) REFERENCES auxiliares (id)');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDFAE21B650 FOREIGN KEY (pac_id) REFERENCES pacientes (id)');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDFCFE419E2 FOREIGN KEY (cv_id) REFERENCES constantes_vitales (id)');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDF237DE2C0 FOREIGN KEY (die_id) REFERENCES dietas (id)');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDFC99EBED9 FOREIGN KEY (mov_id) REFERENCES movilizaciones (id)');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDFAC1F7597 FOREIGN KEY (dia_id) REFERENCES diagnosticos (id)');
        $this->addSql('ALTER TABLE registros ADD CONSTRAINT FK_E78E3BDF344D4453 FOREIGN KEY (dre_id) REFERENCES drenajes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dietas DROP FOREIGN KEY FK_5C6F440E3DC60D6A');
        $this->addSql('ALTER TABLE dieta_tipos_dieta DROP FOREIGN KEY FK_9D9781DB615C2CBC');
        $this->addSql('ALTER TABLE dieta_tipos_dieta DROP FOREIGN KEY FK_9D9781DBF89335CC');
        $this->addSql('ALTER TABLE drenajes DROP FOREIGN KEY FK_2313861BC4CEB6');
        $this->addSql('ALTER TABLE habitaciones DROP FOREIGN KEY FK_E10783BAE21B650');
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDF74D386B2');
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDFAE21B650');
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDFCFE419E2');
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDF237DE2C0');
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDFC99EBED9');
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDFAC1F7597');
        $this->addSql('ALTER TABLE registros DROP FOREIGN KEY FK_E78E3BDF344D4453');
        $this->addSql('DROP TABLE auxiliares');
        $this->addSql('DROP TABLE constantes_vitales');
        $this->addSql('DROP TABLE diagnosticos');
        $this->addSql('DROP TABLE dietas');
        $this->addSql('DROP TABLE dieta_tipos_dieta');
        $this->addSql('DROP TABLE drenajes');
        $this->addSql('DROP TABLE habitaciones');
        $this->addSql('DROP TABLE movilizaciones');
        $this->addSql('DROP TABLE pacientes');
        $this->addSql('DROP TABLE registros');
        $this->addSql('DROP TABLE tipos_dieta');
        $this->addSql('DROP TABLE tipos_drenajes');
        $this->addSql('DROP TABLE tipos_texturas');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
