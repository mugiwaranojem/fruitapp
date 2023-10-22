<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add fruit table.
 */
final class Version20231020193800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add fruit table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fruit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, family VARCHAR(100) DEFAULT NULL, fruit_order VARCHAR(100) DEFAULT NULL, genus VARCHAR(100) DEFAULT NULL, nutritions JSON NOT NULL, `is_favourite` TINYINT(1) NULL DEFAULT 0, `created_at` DATETIME DEFAULT NOW(), updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE fruit');
    }
}
