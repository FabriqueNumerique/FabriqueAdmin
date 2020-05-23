<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200508142855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE apprenant ADD offres_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E6C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id)');
        $this->addSql('CREATE INDEX IDX_C4EB462E6C83CD9F ON apprenant (offres_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E6C83CD9F');
        $this->addSql('DROP INDEX IDX_C4EB462E6C83CD9F ON apprenant');
        $this->addSql('ALTER TABLE apprenant DROP offres_id');
    }
}
