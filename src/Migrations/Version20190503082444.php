<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503082444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE puzzle_world');
        $this->addSql('DROP TABLE quizz_world');
        $this->addSql('ALTER TABLE puzzle ADD world_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE puzzle ADD CONSTRAINT FK_22A6DFDF8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_22A6DFDF8925311C ON puzzle (world_id)');
        $this->addSql('ALTER TABLE world ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE quizz ADD world_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973D8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_7C77973D8925311C ON quizz (world_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE puzzle_world (puzzle_id INT NOT NULL, world_id INT NOT NULL, INDEX IDX_B4B6FFAD9816812 (puzzle_id), INDEX IDX_B4B6FFA8925311C (world_id), PRIMARY KEY(puzzle_id, world_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quizz_world (quizz_id INT NOT NULL, world_id INT NOT NULL, INDEX IDX_C08E1BBD8925311C (world_id), INDEX IDX_C08E1BBDBA934BCD (quizz_id), PRIMARY KEY(quizz_id, world_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE puzzle_world ADD CONSTRAINT FK_B4B6FFA8925311C FOREIGN KEY (world_id) REFERENCES world (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE puzzle_world ADD CONSTRAINT FK_B4B6FFAD9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizz_world ADD CONSTRAINT FK_C08E1BBD8925311C FOREIGN KEY (world_id) REFERENCES world (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizz_world ADD CONSTRAINT FK_C08E1BBDBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE puzzle DROP FOREIGN KEY FK_22A6DFDF8925311C');
        $this->addSql('DROP INDEX IDX_22A6DFDF8925311C ON puzzle');
        $this->addSql('ALTER TABLE puzzle DROP world_id');
        $this->addSql('ALTER TABLE quizz DROP FOREIGN KEY FK_7C77973D8925311C');
        $this->addSql('DROP INDEX IDX_7C77973D8925311C ON quizz');
        $this->addSql('ALTER TABLE quizz DROP world_id');
        $this->addSql('ALTER TABLE world DROP image');
    }
}
