<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204141731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO articles (name) VALUES ('Lorem')");
        $this->addSql("INSERT INTO articles (name) VALUES ('Ipsum')");
        $this->addSql("INSERT INTO articles (name) VALUES ('Test')");
        $this->addSql("INSERT INTO articles (name) VALUES ('Article')");
        $this->addSql("INSERT INTO articles (name) VALUES ('Jimmy')");
        $this->addSql("INSERT INTO articles (name) VALUES ('Testcase')");
        $this->addSql("INSERT INTO articles (name) VALUES ('Any article name')");
        $this->addSql("INSERT INTO articles (name) VALUES ('Data')");
        $this->addSql("INSERT INTO articles (name) VALUES ('PHP')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
