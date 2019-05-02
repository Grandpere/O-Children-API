<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190502150720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE puzzle (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, image VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE puzzle_category (puzzle_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_5210F490D9816812 (puzzle_id), INDEX IDX_5210F49012469DE2 (category_id), PRIMARY KEY(puzzle_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE puzzle_world (puzzle_id INT NOT NULL, world_id INT NOT NULL, INDEX IDX_B4B6FFAD9816812 (puzzle_id), INDEX IDX_B4B6FFA8925311C (world_id), PRIMARY KEY(puzzle_id, world_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE world (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, right_answer_id INT DEFAULT NULL, quizz_id INT DEFAULT NULL, content VARCHAR(200) NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B6F7494E4C827E5E (right_answer_id), INDEX IDX_B6F7494EBA934BCD (quizz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, label VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, image VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(150) NOT NULL, firstname VARCHAR(150) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_quizz (user_id INT NOT NULL, quizz_id INT NOT NULL, INDEX IDX_9EB56C65A76ED395 (user_id), INDEX IDX_9EB56C65BA934BCD (quizz_id), PRIMARY KEY(user_id, quizz_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_puzzle (user_id INT NOT NULL, puzzle_id INT NOT NULL, INDEX IDX_47F4C4E2A76ED395 (user_id), INDEX IDX_47F4C4E2D9816812 (puzzle_id), PRIMARY KEY(user_id, puzzle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE play_quizz (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, quizz_id INT NOT NULL, INDEX IDX_8DA87081A76ED395 (user_id), INDEX IDX_8DA87081BA934BCD (quizz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, content VARCHAR(200) NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE make_puzzle (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, puzzle_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_CE265319A76ED395 (user_id), INDEX IDX_CE265319D9816812 (puzzle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, image VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz_category (quizz_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_8798E5D3BA934BCD (quizz_id), INDEX IDX_8798E5D312469DE2 (category_id), PRIMARY KEY(quizz_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz_world (quizz_id INT NOT NULL, world_id INT NOT NULL, INDEX IDX_C08E1BBDBA934BCD (quizz_id), INDEX IDX_C08E1BBD8925311C (world_id), PRIMARY KEY(quizz_id, world_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE puzzle_category ADD CONSTRAINT FK_5210F490D9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE puzzle_category ADD CONSTRAINT FK_5210F49012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE puzzle_world ADD CONSTRAINT FK_B4B6FFAD9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE puzzle_world ADD CONSTRAINT FK_B4B6FFA8925311C FOREIGN KEY (world_id) REFERENCES world (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E4C827E5E FOREIGN KEY (right_answer_id) REFERENCES answer (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user_quizz ADD CONSTRAINT FK_9EB56C65A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_quizz ADD CONSTRAINT FK_9EB56C65BA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_puzzle ADD CONSTRAINT FK_47F4C4E2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_puzzle ADD CONSTRAINT FK_47F4C4E2D9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE play_quizz ADD CONSTRAINT FK_8DA87081A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE play_quizz ADD CONSTRAINT FK_8DA87081BA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE make_puzzle ADD CONSTRAINT FK_CE265319A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE make_puzzle ADD CONSTRAINT FK_CE265319D9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id)');
        $this->addSql('ALTER TABLE quizz_category ADD CONSTRAINT FK_8798E5D3BA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizz_category ADD CONSTRAINT FK_8798E5D312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizz_world ADD CONSTRAINT FK_C08E1BBDBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizz_world ADD CONSTRAINT FK_C08E1BBD8925311C FOREIGN KEY (world_id) REFERENCES world (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE puzzle_category DROP FOREIGN KEY FK_5210F490D9816812');
        $this->addSql('ALTER TABLE puzzle_world DROP FOREIGN KEY FK_B4B6FFAD9816812');
        $this->addSql('ALTER TABLE user_puzzle DROP FOREIGN KEY FK_47F4C4E2D9816812');
        $this->addSql('ALTER TABLE make_puzzle DROP FOREIGN KEY FK_CE265319D9816812');
        $this->addSql('ALTER TABLE puzzle_world DROP FOREIGN KEY FK_B4B6FFA8925311C');
        $this->addSql('ALTER TABLE quizz_world DROP FOREIGN KEY FK_C08E1BBD8925311C');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE puzzle_category DROP FOREIGN KEY FK_5210F49012469DE2');
        $this->addSql('ALTER TABLE quizz_category DROP FOREIGN KEY FK_8798E5D312469DE2');
        $this->addSql('ALTER TABLE user_quizz DROP FOREIGN KEY FK_9EB56C65A76ED395');
        $this->addSql('ALTER TABLE user_puzzle DROP FOREIGN KEY FK_47F4C4E2A76ED395');
        $this->addSql('ALTER TABLE play_quizz DROP FOREIGN KEY FK_8DA87081A76ED395');
        $this->addSql('ALTER TABLE make_puzzle DROP FOREIGN KEY FK_CE265319A76ED395');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E4C827E5E');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBA934BCD');
        $this->addSql('ALTER TABLE user_quizz DROP FOREIGN KEY FK_9EB56C65BA934BCD');
        $this->addSql('ALTER TABLE play_quizz DROP FOREIGN KEY FK_8DA87081BA934BCD');
        $this->addSql('ALTER TABLE quizz_category DROP FOREIGN KEY FK_8798E5D3BA934BCD');
        $this->addSql('ALTER TABLE quizz_world DROP FOREIGN KEY FK_C08E1BBDBA934BCD');
        $this->addSql('DROP TABLE puzzle');
        $this->addSql('DROP TABLE puzzle_category');
        $this->addSql('DROP TABLE puzzle_world');
        $this->addSql('DROP TABLE world');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_quizz');
        $this->addSql('DROP TABLE user_puzzle');
        $this->addSql('DROP TABLE play_quizz');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE make_puzzle');
        $this->addSql('DROP TABLE quizz');
        $this->addSql('DROP TABLE quizz_category');
        $this->addSql('DROP TABLE quizz_world');
    }
}
