<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113105916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consommable (id INT AUTO_INCREMENT NOT NULL, logement_id_id INT DEFAULT NULL, nom_magasin VARCHAR(255) NOT NULL, prix INT NOT NULL, INDEX IDX_A04C7F4D884C09A7 (logement_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consommable ADD CONSTRAINT FK_A04C7F4D884C09A7 FOREIGN KEY (logement_id_id) REFERENCES logement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consommable DROP FOREIGN KEY FK_A04C7F4D884C09A7');
        $this->addSql('DROP TABLE consommable');
    }
}
