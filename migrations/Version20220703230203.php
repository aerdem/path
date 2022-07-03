<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220703230203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" ADD COLUMN created_by INTEGER NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__order AS SELECT id, order_code, product_id, quantity, address, shipping_date FROM "order"');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, order_code VARCHAR(255) NOT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL, address VARCHAR(255) NOT NULL, shipping_date DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO "order" (id, order_code, product_id, quantity, address, shipping_date) SELECT id, order_code, product_id, quantity, address, shipping_date FROM __temp__order');
        $this->addSql('DROP TABLE __temp__order');
    }
}
