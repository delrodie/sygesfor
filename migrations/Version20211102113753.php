<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102113753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Candidat (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, carteScoute VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenoms VARCHAR(255) DEFAULT NULL, dateNaissance VARCHAR(255) DEFAULT NULL, lieuNaissance VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, dateEntree VARCHAR(255) DEFAULT NULL, niveauEtude VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, residence VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, idTransaction VARCHAR(255) DEFAULT NULL, statusPaiement VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, createAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, INDEX IDX_93C3D62798260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Candidat ADD CONSTRAINT FK_93C3D62798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Candidat');
    }
}
