<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260129142722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD proprietaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD concierge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER type_user SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64976C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649A3DF579D FOREIGN KEY (concierge_id) REFERENCES concierge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64976C50E4A ON "user" (proprietaire_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A3DF579D ON "user" (concierge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64976C50E4A');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649A3DF579D');
        $this->addSql('DROP INDEX UNIQ_8D93D64976C50E4A');
        $this->addSql('DROP INDEX UNIQ_8D93D649A3DF579D');
        $this->addSql('ALTER TABLE "user" DROP proprietaire_id');
        $this->addSql('ALTER TABLE "user" DROP concierge_id');
        $this->addSql('ALTER TABLE "user" ALTER type_user DROP NOT NULL');
    }
}
