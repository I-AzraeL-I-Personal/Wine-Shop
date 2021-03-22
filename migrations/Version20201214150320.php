<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214150320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product_category DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_149244D312469DE2');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_149244D34584665A');
        $this->addSql('ALTER TABLE product_category ADD PRIMARY KEY (product_id, category_id)');
        $this->addSql('DROP INDEX idx_149244d34584665a ON product_category');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
        $this->addSql('DROP INDEX idx_149244d312469de2 ON product_category');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_149244D312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_149244D34584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP link');
        $this->addSql('ALTER TABLE product_category DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE product_category ADD PRIMARY KEY (category_id, product_id)');
        $this->addSql('DROP INDEX idx_cdfc735612469de2 ON product_category');
        $this->addSql('CREATE INDEX IDX_149244D312469DE2 ON product_category (category_id)');
        $this->addSql('DROP INDEX idx_cdfc73564584665a ON product_category');
        $this->addSql('CREATE INDEX IDX_149244D34584665A ON product_category (product_id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }
}
