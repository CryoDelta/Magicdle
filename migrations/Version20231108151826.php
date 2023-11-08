<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108151826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, last_set_id INT NOT NULL, name VARCHAR(255) NOT NULL, cost VARCHAR(255) NOT NULL, colors VARCHAR(255) NOT NULL, mana_value INT NOT NULL, power DOUBLE PRECISION DEFAULT NULL, toughness DOUBLE PRECISION DEFAULT NULL, loyalty INT DEFAULT NULL, defense INT DEFAULT NULL, typeline VARCHAR(255) NOT NULL, effect_text LONGTEXT DEFAULT NULL, flavor_text LONGTEXT DEFAULT NULL, color_identity VARCHAR(255) NOT NULL, rarity VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, INDEX IDX_161498D39AFC8FC2 (last_set_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_keyword (card_id INT NOT NULL, keyword_id INT NOT NULL, INDEX IDX_D89FB4D4ACC9A20 (card_id), INDEX IDX_D89FB4D115D4552 (keyword_id), PRIMARY KEY(card_id, keyword_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE keyword (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, alt VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10C4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `set` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, release_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D39AFC8FC2 FOREIGN KEY (last_set_id) REFERENCES `set` (id)');
        $this->addSql('ALTER TABLE card_keyword ADD CONSTRAINT FK_D89FB4D4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_keyword ADD CONSTRAINT FK_D89FB4D115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D39AFC8FC2');
        $this->addSql('ALTER TABLE card_keyword DROP FOREIGN KEY FK_D89FB4D4ACC9A20');
        $this->addSql('ALTER TABLE card_keyword DROP FOREIGN KEY FK_D89FB4D115D4552');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C4ACC9A20');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_keyword');
        $this->addSql('DROP TABLE keyword');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE `set`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
