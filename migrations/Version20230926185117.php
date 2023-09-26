<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926185117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vinyl_mix ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_38F28AFB989D9B62 ON vinyl_mix (slug)');
        $this->addSql("UPDATE vinyl_mix SET slug=CONCAT(REPLACE(LOWER(title), ' ', '_'), '-', id)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_38F28AFB989D9B62 ON vinyl_mix');
        $this->addSql('ALTER TABLE vinyl_mix DROP slug');
    }
}
