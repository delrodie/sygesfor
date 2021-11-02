<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102143026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Candidater (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, activite_id INT DEFAULT NULL, validation TINYINT(1) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_522DCB4A8D0EB82 (candidat_id), INDEX IDX_522DCB4A9B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Candidater ADD CONSTRAINT FK_522DCB4A8D0EB82 FOREIGN KEY (candidat_id) REFERENCES Candidat (id)');
        $this->addSql('ALTER TABLE Candidater ADD CONSTRAINT FK_522DCB4A9B0F88B1 FOREIGN KEY (activite_id) REFERENCES Activite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Candidater');
    }
}
