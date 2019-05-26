<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190526135010 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE pre_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_EF82FC73A76ED395 ON pre_order (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__basket AS SELECT id, user_id_id, status FROM basket');
        $this->addSql('DROP TABLE basket');
        $this->addSql('CREATE TABLE basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, date_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_2246507B9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2246507BB897366B FOREIGN KEY (date_id) REFERENCES pre_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO basket (id, user_id_id, status) SELECT id, user_id_id, status FROM __temp__basket');
        $this->addSql('DROP TABLE __temp__basket');
        $this->addSql('CREATE INDEX IDX_2246507B9D86650F ON basket (user_id_id)');
        $this->addSql('CREATE INDEX IDX_2246507BB897366B ON basket (date_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__basket_product AS SELECT basket_id, product_id FROM basket_product');
        $this->addSql('DROP TABLE basket_product');
        $this->addSql('CREATE TABLE basket_product (basket_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(basket_id, product_id), CONSTRAINT FK_17ED14B41BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_17ED14B44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO basket_product (basket_id, product_id) SELECT basket_id, product_id FROM __temp__basket_product');
        $this->addSql('DROP TABLE __temp__basket_product');
        $this->addSql('CREATE INDEX IDX_17ED14B41BE1FB52 ON basket_product (basket_id)');
        $this->addSql('CREATE INDEX IDX_17ED14B44584665A ON basket_product (product_id)');
        $this->addSql('DROP INDEX IDX_51498A8E88987678');
        $this->addSql('DROP INDEX IDX_51498A8E9D86650F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__users_roles AS SELECT id, user_id_id, role_id_id FROM users_roles');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('CREATE TABLE users_roles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, role_id_id INTEGER NOT NULL, CONSTRAINT FK_51498A8E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_51498A8E88987678 FOREIGN KEY (role_id_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO users_roles (id, user_id_id, role_id_id) SELECT id, user_id_id, role_id_id FROM __temp__users_roles');
        $this->addSql('DROP TABLE __temp__users_roles');
        $this->addSql('CREATE INDEX IDX_51498A8E88987678 ON users_roles (role_id_id)');
        $this->addSql('CREATE INDEX IDX_51498A8E9D86650F ON users_roles (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE pre_order');
        $this->addSql('DROP INDEX IDX_2246507B9D86650F');
        $this->addSql('DROP INDEX IDX_2246507BB897366B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__basket AS SELECT id, user_id_id, status FROM basket');
        $this->addSql('DROP TABLE basket');
        $this->addSql('CREATE TABLE basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO basket (id, user_id_id, status) SELECT id, user_id_id, status FROM __temp__basket');
        $this->addSql('DROP TABLE __temp__basket');
        $this->addSql('DROP INDEX IDX_17ED14B41BE1FB52');
        $this->addSql('DROP INDEX IDX_17ED14B44584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__basket_product AS SELECT basket_id, product_id FROM basket_product');
        $this->addSql('DROP TABLE basket_product');
        $this->addSql('CREATE TABLE basket_product (basket_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(basket_id, product_id))');
        $this->addSql('INSERT INTO basket_product (basket_id, product_id) SELECT basket_id, product_id FROM __temp__basket_product');
        $this->addSql('DROP TABLE __temp__basket_product');
        $this->addSql('DROP INDEX IDX_51498A8E9D86650F');
        $this->addSql('DROP INDEX IDX_51498A8E88987678');
        $this->addSql('CREATE TEMPORARY TABLE __temp__users_roles AS SELECT id, user_id_id, role_id_id FROM users_roles');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('CREATE TABLE users_roles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, role_id_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO users_roles (id, user_id_id, role_id_id) SELECT id, user_id_id, role_id_id FROM __temp__users_roles');
        $this->addSql('DROP TABLE __temp__users_roles');
        $this->addSql('CREATE INDEX IDX_51498A8E9D86650F ON users_roles (user_id_id)');
        $this->addSql('CREATE INDEX IDX_51498A8E88987678 ON users_roles (role_id_id)');
    }
}
