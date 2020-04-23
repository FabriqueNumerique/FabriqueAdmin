<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200422113000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contacts (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, INDEX IDX_33401573A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apprenant (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, git VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apprenant_promotion (apprenant_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_9675178DC5697D6D (apprenant_id), INDEX IDX_9675178D139DF194 (promotion_id), PRIMARY KEY(apprenant_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reseau (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, INDEX IDX_CDE52CB8C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, nombre_heure INT NOT NULL, diplome VARCHAR(255) NOT NULL, modalites VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal INT NOT NULL, ville VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, intitule VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, cahier_des_charges VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C6AC3544C5697D6D (apprenant_id), INDEX IDX_C6AC3544A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, annee INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, nombre_eleves INT NOT NULL, commentaires VARCHAR(255) DEFAULT NULL, INDEX IDX_C11D7DD15200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_promotion ADD CONSTRAINT FK_9675178DC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_promotion ADD CONSTRAINT FK_9675178D139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reseau ADD CONSTRAINT FK_CDE52CB8C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD15200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE apprenant_promotion DROP FOREIGN KEY FK_9675178DC5697D6D');
        $this->addSql('ALTER TABLE reseau DROP FOREIGN KEY FK_CDE52CB8C5697D6D');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544C5697D6D');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EBF396750');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD15200282E');
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_33401573A4AEAFEA');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544A4AEAFEA');
        $this->addSql('ALTER TABLE apprenant_promotion DROP FOREIGN KEY FK_9675178D139DF194');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE apprenant');
        $this->addSql('DROP TABLE apprenant_promotion');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE reseau');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE promotion');
    }
}
