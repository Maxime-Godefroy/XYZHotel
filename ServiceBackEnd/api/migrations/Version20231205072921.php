<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205072921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comptes_clients DROP FOREIGN KEY FK_92F52D43DC2902E0');
        $this->addSql('DROP INDEX UNIQ_92F52D43DC2902E0 ON comptes_clients');
        $this->addSql('ALTER TABLE comptes_clients CHANGE client_id_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comptes_clients ADD CONSTRAINT FK_92F52D4319EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92F52D4319EB6921 ON comptes_clients (client_id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239DC2902E0');
        $this->addSql('DROP INDEX IDX_4DA239DC2902E0 ON reservations');
        $this->addSql('ALTER TABLE reservations CHANGE client_id_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23919EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_4DA23919EB6921 ON reservations (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comptes_clients DROP FOREIGN KEY FK_92F52D4319EB6921');
        $this->addSql('DROP INDEX UNIQ_92F52D4319EB6921 ON comptes_clients');
        $this->addSql('ALTER TABLE comptes_clients CHANGE client_id client_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comptes_clients ADD CONSTRAINT FK_92F52D43DC2902E0 FOREIGN KEY (client_id_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92F52D43DC2902E0 ON comptes_clients (client_id_id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23919EB6921');
        $this->addSql('DROP INDEX IDX_4DA23919EB6921 ON reservations');
        $this->addSql('ALTER TABLE reservations CHANGE client_id client_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239DC2902E0 FOREIGN KEY (client_id_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4DA239DC2902E0 ON reservations (client_id_id)');
    }
}
