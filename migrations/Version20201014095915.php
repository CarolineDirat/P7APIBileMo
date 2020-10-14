<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014095915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (bm_id INT AUTO_INCREMENT NOT NULL, bm_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', bm_username VARCHAR(180) NOT NULL, bm_roles JSON NOT NULL, bm_password VARCHAR(255) NOT NULL, bm_email VARCHAR(200) NOT NULL, bm_createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', bm_updatedAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_C74404557F574996 (bm_uuid), UNIQUE INDEX UNIQ_C744045577B1466C (bm_username), UNIQUE INDEX UNIQ_C7440455C1E564C1 (bm_email), PRIMARY KEY(bm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone (bm_id INT AUTO_INCREMENT NOT NULL, size_bm_id INT DEFAULT NULL, screen_bm_id INT DEFAULT NULL, bm_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', bm_created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', bm_updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', bm_constructor VARCHAR(55) NOT NULL, bm_name VARCHAR(55) NOT NULL, bm_priceEuro DOUBLE PRECISION NOT NULL, bm_system VARCHAR(45) DEFAULT NULL, bm_user_interface VARCHAR(45) DEFAULT NULL, bm_processor VARCHAR(45) DEFAULT NULL, bm_ram VARCHAR(6) DEFAULT NULL, bm_capacity VARCHAR(10) DEFAULT NULL, bm_das VARCHAR(15) DEFAULT NULL, bm_battery_capacity VARCHAR(10) DEFAULT NULL, bm_wireless_charging TINYINT(1) DEFAULT NULL, bm_weight VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_444F97DD7F574996 (bm_uuid), UNIQUE INDEX UNIQ_444F97DDA44C22FB (size_bm_id), UNIQUE INDEX UNIQ_444F97DD89BC088 (screen_bm_id), PRIMARY KEY(bm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE screen (bm_id INT AUTO_INCREMENT NOT NULL, phone_bm_id INT NOT NULL, bm_size VARCHAR(8) DEFAULT NULL, bm_technology VARCHAR(45) DEFAULT NULL, bm_definition VARCHAR(15) DEFAULT NULL, bm_resolution VARCHAR(8) DEFAULT NULL, bm_refresh_rate VARCHAR(7) DEFAULT NULL, UNIQUE INDEX UNIQ_DF4C613086C695D6 (phone_bm_id), PRIMARY KEY(bm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE size (bm_id INT AUTO_INCREMENT NOT NULL, phone_bm_id INT NOT NULL, bm_width VARCHAR(10) NOT NULL, bm_height VARCHAR(10) NOT NULL, bm_thickness VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_F7C0246A86C695D6 (phone_bm_id), PRIMARY KEY(bm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (bm_id INT AUTO_INCREMENT NOT NULL, client_bm_id INT NOT NULL, bm_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', bm_email VARCHAR(200) NOT NULL, bm_password VARCHAR(255) NOT NULL, bm_firstname VARCHAR(45) NOT NULL, bm_lastname VARCHAR(45) NOT NULL, bm_created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', bm_updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D6497F574996 (bm_uuid), INDEX IDX_8D93D649D6F17C7B (client_bm_id), PRIMARY KEY(bm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDA44C22FB FOREIGN KEY (size_bm_id) REFERENCES size (bm_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD89BC088 FOREIGN KEY (screen_bm_id) REFERENCES screen (bm_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE screen ADD CONSTRAINT FK_DF4C613086C695D6 FOREIGN KEY (phone_bm_id) REFERENCES phone (bm_id)');
        $this->addSql('ALTER TABLE size ADD CONSTRAINT FK_F7C0246A86C695D6 FOREIGN KEY (phone_bm_id) REFERENCES phone (bm_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D6F17C7B FOREIGN KEY (client_bm_id) REFERENCES client (bm_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D6F17C7B');
        $this->addSql('ALTER TABLE screen DROP FOREIGN KEY FK_DF4C613086C695D6');
        $this->addSql('ALTER TABLE size DROP FOREIGN KEY FK_F7C0246A86C695D6');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD89BC088');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDA44C22FB');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE phone');
        $this->addSql('DROP TABLE screen');
        $this->addSql('DROP TABLE size');
        $this->addSql('DROP TABLE user');
    }
}
