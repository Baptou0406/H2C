<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108141345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB884C09A7');
        $this->addSql('DROP INDEX IDX_D11814AB884C09A7 ON intervention');
        $this->addSql('ALTER TABLE intervention CHANGE logement_id_id logement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB58ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_D11814AB58ABF955 ON intervention (logement_id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955884C09A7');
        $this->addSql('DROP INDEX IDX_42C84955884C09A7 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE logement_id_id logement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495558ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_42C8495558ABF955 ON reservation (logement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495558ABF955');
        $this->addSql('DROP INDEX IDX_42C8495558ABF955 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE logement_id logement_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955884C09A7 FOREIGN KEY (logement_id_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_42C84955884C09A7 ON reservation (logement_id_id)');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB58ABF955');
        $this->addSql('DROP INDEX IDX_D11814AB58ABF955 ON intervention');
        $this->addSql('ALTER TABLE intervention CHANGE logement_id logement_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB884C09A7 FOREIGN KEY (logement_id_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_D11814AB884C09A7 ON intervention (logement_id_id)');
    }
}
