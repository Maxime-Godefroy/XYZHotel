<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207113637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambres (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(50) NOT NULL, prix_nuit NUMERIC(10, 2) NOT NULL, capacite INT NOT NULL, caracteristiques LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chambres_reservees (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, chambre_id INT NOT NULL, INDEX IDX_6FB472E1B83297E7 (reservation_id), INDEX IDX_6FB472E19B177F54 (chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, adresse_mail VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, numero_telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comptes_clients (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, solde_portefeuille NUMERIC(10, 2) DEFAULT NULL, UNIQUE INDEX UNIQ_92F52D4319EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, date_checkin DATE NOT NULL, nombre_nuits INT NOT NULL, statut VARCHAR(20) NOT NULL, INDEX IDX_4DA23919EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chambres_reservees ADD CONSTRAINT FK_6FB472E1B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservations (id)');
        $this->addSql('ALTER TABLE chambres_reservees ADD CONSTRAINT FK_6FB472E19B177F54 FOREIGN KEY (chambre_id) REFERENCES chambres (id)');
        $this->addSql('ALTER TABLE comptes_clients ADD CONSTRAINT FK_92F52D4319EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23919EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chambres_reservees DROP FOREIGN KEY FK_6FB472E1B83297E7');
        $this->addSql('ALTER TABLE chambres_reservees DROP FOREIGN KEY FK_6FB472E19B177F54');
        $this->addSql('ALTER TABLE comptes_clients DROP FOREIGN KEY FK_92F52D4319EB6921');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23919EB6921');
        $this->addSql('DROP TABLE chambres');
        $this->addSql('DROP TABLE chambres_reservees');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE comptes_clients');
        $this->addSql('DROP TABLE reservations');
    }
}
