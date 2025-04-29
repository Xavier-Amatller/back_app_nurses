<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424145454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tipos_drenajes (id INT AUTO_INCREMENT NOT NULL, tdre_desc VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE drenajes ADD tdre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE drenajes ADD CONSTRAINT FK_2313861BC4CEB6 FOREIGN KEY (tdre_id) REFERENCES tipos_drenajes (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2313861BC4CEB6 ON drenajes (tdre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drenajes DROP FOREIGN KEY FK_2313861BC4CEB6');
        $this->addSql('DROP TABLE tipos_drenajes');
        $this->addSql('DROP INDEX UNIQ_2313861BC4CEB6 ON drenajes');
        $this->addSql('ALTER TABLE drenajes DROP tdre_id');
    }
}
