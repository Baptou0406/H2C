<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108085326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consommable DROP FOREIGN KEY FK_A04C7F4D884C09A7');
        $this->addSql('DROP INDEX IDX_A04C7F4D884C09A7 ON consommable');
        $this->addSql('ALTER TABLE consommable CHANGE logement_id_id logement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consommable ADD CONSTRAINT FK_A04C7F4D58ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_A04C7F4D58ABF955 ON consommable (logement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consommable DROP FOREIGN KEY FK_A04C7F4D58ABF955');
        $this->addSql('DROP INDEX IDX_A04C7F4D58ABF955 ON consommable');
        $this->addSql('ALTER TABLE consommable CHANGE logement_id logement_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consommable ADD CONSTRAINT FK_A04C7F4D884C09A7 FOREIGN KEY (logement_id_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_A04C7F4D884C09A7 ON consommable (logement_id_id)');
    }
}
