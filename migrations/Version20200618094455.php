<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618094455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add movie, genre and keyword tables also movie_genre and movie_keyword pivot tables ';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE genre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE keyword_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE movie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE genre (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE keyword (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE movie (id INT NOT NULL, title VARCHAR(255) NOT NULL, overview TEXT NOT NULL, budget DOUBLE PRECISION NOT NULL, runtime INT NOT NULL, release_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE movie_genre (movie_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY(movie_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_FD1229648F93B6FC ON movie_genre (movie_id)');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
        $this->addSql('CREATE TABLE movie_keyword (movie_id INT NOT NULL, keyword_id INT NOT NULL, PRIMARY KEY(movie_id, keyword_id))');
        $this->addSql('CREATE INDEX IDX_7A0533158F93B6FC ON movie_keyword (movie_id)');
        $this->addSql('CREATE INDEX IDX_7A053315115D4552 ON movie_keyword (keyword_id)');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_keyword ADD CONSTRAINT FK_7A0533158F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_keyword ADD CONSTRAINT FK_7A053315115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229644296D31F');
        $this->addSql('ALTER TABLE movie_keyword DROP CONSTRAINT FK_7A053315115D4552');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229648F93B6FC');
        $this->addSql('ALTER TABLE movie_keyword DROP CONSTRAINT FK_7A0533158F93B6FC');
        $this->addSql('DROP SEQUENCE genre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE keyword_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE movie_id_seq CASCADE');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE keyword');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('DROP TABLE movie_keyword');
    }
}
