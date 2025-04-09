<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409134059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dieta_tipos_dieta (dieta_id INT NOT NULL, tipos_dieta_id INT NOT NULL, INDEX IDX_9D9781DB615C2CBC (dieta_id), INDEX IDX_9D9781DBF89335CC (tipos_dieta_id), PRIMARY KEY(dieta_id, tipos_dieta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dieta_tipos_dieta ADD CONSTRAINT FK_9D9781DB615C2CBC FOREIGN KEY (dieta_id) REFERENCES dietas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dieta_tipos_dieta ADD CONSTRAINT FK_9D9781DBF89335CC FOREIGN KEY (tipos_dieta_id) REFERENCES tipos_dieta (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dieta_tipos_dieta DROP FOREIGN KEY FK_9D9781DB615C2CBC');
        $this->addSql('ALTER TABLE dieta_tipos_dieta DROP FOREIGN KEY FK_9D9781DBF89335CC');
        $this->addSql('DROP TABLE dieta_tipos_dieta');
    }
}
