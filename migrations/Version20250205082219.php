<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205082219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consommable CHANGE logement_id logement_id INT NOT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE logement ADD adresse VARCHAR(255) NOT NULL, DROP commission, DROP menage');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consommable CHANGE logement_id logement_id INT DEFAULT NULL, CHANGE prix prix INT NOT NULL');
        $this->addSql('ALTER TABLE logement ADD commission INT NOT NULL, ADD menage INT NOT NULL, DROP adresse');
    }
}
