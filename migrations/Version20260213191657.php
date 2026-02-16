<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213191657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appartement ALTER numero TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE appartement ALTER code_cle TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE appartement ALTER code_porte TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appartement ALTER numero TYPE INT');
        $this->addSql('ALTER TABLE appartement ALTER code_cle TYPE INT');
        $this->addSql('ALTER TABLE appartement ALTER code_porte TYPE INT');
    }
}
