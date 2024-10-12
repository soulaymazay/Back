<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230624165423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesmoyens ADD has_image TINYINT(1) NOT NULL, DROP image');
        $this->addSql('ALTER TABLE user ADD has_image TINYINT(1) NOT NULL, DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesmoyens ADD image LONGBLOB DEFAULT NULL, DROP has_image');
        $this->addSql('ALTER TABLE user ADD image LONGTEXT DEFAULT NULL, DROP has_image');
    }
}
