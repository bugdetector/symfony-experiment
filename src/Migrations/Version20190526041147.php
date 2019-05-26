<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190526041147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2246507B9D86650F ON basket (user_id_id)');
        $this->addSql('CREATE TABLE basket_product (basket_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(basket_id, product_id))');
        $this->addSql('CREATE INDEX IDX_17ED14B41BE1FB52 ON basket_product (basket_id)');
        $this->addSql('CREATE INDEX IDX_17ED14B44584665A ON basket_product (product_id)');
        $this->addSql('CREATE TABLE users_roles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, role_id_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_51498A8E9D86650F ON users_roles (user_id_id)');
        $this->addSql('CREATE INDEX IDX_51498A8E88987678 ON users_roles (role_id_id)');
        $this->addSql('CREATE TABLE role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, phone VARCHAR(14) DEFAULT NULL, status VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, create_date VARCHAR(255) DEFAULT NULL, last_access VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE basket_product');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
    }
}
