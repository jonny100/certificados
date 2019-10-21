<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191021161337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE certificado_evento DROP FOREIGN KEY fk_certificado_evento_certificado1');
        $this->addSql('ALTER TABLE certificado_evento_requisito DROP FOREIGN KEY fk_certificado_evento_requisito_certificado_evento1');
        $this->addSql('ALTER TABLE inscripto_certificado DROP FOREIGN KEY fk_inscripto_certificado_certificado_evento1');
        $this->addSql('ALTER TABLE inscripto_evento_requisito DROP FOREIGN KEY fk_inscripto_evento_requisito_certificado_evento_requisito1');
        $this->addSql('ALTER TABLE certificado_evento DROP FOREIGN KEY fk_certificado_evento_evento1');
        $this->addSql('ALTER TABLE inscripto DROP FOREIGN KEY fk_inscripto_evento1');
        $this->addSql('ALTER TABLE inscripto_certificado DROP FOREIGN KEY fk_inscripto_certificado_inscripto1');
        $this->addSql('ALTER TABLE inscripto_evento_requisito DROP FOREIGN KEY fk_inscripto_evento_requisito_inscripto1');
        $this->addSql('ALTER TABLE inscripto DROP FOREIGN KEY fk_inscripto_persona1');
        $this->addSql('ALTER TABLE certificado_evento_requisito DROP FOREIGN KEY fk_evento_requisito_requisito1');
        $this->addSql('ALTER TABLE certificado_evento DROP FOREIGN KEY fk_certificado_evento_template1');
        $this->addSql('ALTER TABLE certificado DROP FOREIGN KEY fk_certificados_tipo_certificado');
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY fk_evento_tipo_evento1');
        $this->addSql('DROP TABLE certificado');
        $this->addSql('DROP TABLE certificado_evento');
        $this->addSql('DROP TABLE certificado_evento_requisito');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE inscripto');
        $this->addSql('DROP TABLE inscripto_certificado');
        $this->addSql('DROP TABLE inscripto_evento_requisito');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE requisito');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE tipo_certificado');
        $this->addSql('DROP TABLE tipo_evento');
        $this->addSql('ALTER TABLE fos_user_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE date_of_birth date_of_birth DATETIME DEFAULT NULL, CHANGE firstname firstname VARCHAR(64) DEFAULT NULL, CHANGE lastname lastname VARCHAR(64) DEFAULT NULL, CHANGE website website VARCHAR(64) DEFAULT NULL, CHANGE biography biography VARCHAR(1000) DEFAULT NULL, CHANGE gender gender VARCHAR(1) DEFAULT NULL, CHANGE locale locale VARCHAR(8) DEFAULT NULL, CHANGE timezone timezone VARCHAR(64) DEFAULT NULL, CHANGE phone phone VARCHAR(64) DEFAULT NULL, CHANGE facebook_uid facebook_uid VARCHAR(255) DEFAULT NULL, CHANGE facebook_name facebook_name VARCHAR(255) DEFAULT NULL, CHANGE facebook_data facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE twitter_uid twitter_uid VARCHAR(255) DEFAULT NULL, CHANGE twitter_name twitter_name VARCHAR(255) DEFAULT NULL, CHANGE twitter_data twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE gplus_uid gplus_uid VARCHAR(255) DEFAULT NULL, CHANGE gplus_name gplus_name VARCHAR(255) DEFAULT NULL, CHANGE gplus_data gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE two_step_code two_step_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE certificado (id INT AUTO_INCREMENT NOT NULL, tipo_certificado_id INT NOT NULL, descripcion VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, estado INT DEFAULT 1, INDEX fk_certificados_tipo_certificado_idx (tipo_certificado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE certificado_evento (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, certificado_id INT NOT NULL, evento_id INT NOT NULL, INDEX fk_certificado_evento_evento1_idx (evento_id), INDEX fk_certificado_evento_certificado1_idx (certificado_id), INDEX fk_certificado_evento_template1_idx (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE certificado_evento_requisito (id INT AUTO_INCREMENT NOT NULL, requisito_id INT NOT NULL, certificado_evento_id INT NOT NULL, INDEX fk_certificado_evento_requisito_certificado_evento1_idx (certificado_evento_id), INDEX fk_evento_requisito_requisito1_idx (requisito_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evento (id INT AUTO_INCREMENT NOT NULL, tipo_evento_id INT NOT NULL, descripcion VARCHAR(450) DEFAULT \'NULL\' COLLATE utf8_general_ci, fecha_ini DATETIME DEFAULT \'NULL\', fecha_fin DATETIME DEFAULT \'NULL\', cupo INT DEFAULT NULL, evento_sgi_id INT DEFAULT NULL, estado INT DEFAULT 1, INDEX fk_evento_tipo_evento1_idx (tipo_evento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE inscripto (id INT AUTO_INCREMENT NOT NULL, evento_id INT NOT NULL, persona_id INT NOT NULL, fecha_insc DATETIME DEFAULT \'NULL\', estado INT DEFAULT 1, INDEX fk_inscripto_persona1_idx (persona_id), INDEX fk_inscripto_evento1_idx (evento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE inscripto_certificado (id INT AUTO_INCREMENT NOT NULL, inscripto_id INT NOT NULL, certificado_evento_id INT NOT NULL, fecha_obt DATETIME DEFAULT \'NULL\', estado INT DEFAULT 1, INDEX fk_inscripto_certificado_certificado_evento1_idx (certificado_evento_id), INDEX fk_inscripto_certificado_inscripto1_idx (inscripto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE inscripto_evento_requisito (id INT AUTO_INCREMENT NOT NULL, inscripto_id INT NOT NULL, certificado_evento_requisito_id INT NOT NULL, excluir TINYINT(1) DEFAULT \'0\' COMMENT \'para casos en que un inscripto no necesite cumplir el requisito\', INDEX fk_inscripto_evento_requisito_certificado_evento_requisito1_idx (certificado_evento_requisito_id), INDEX fk_inscripto_evento_requisito_inscripto1_idx (inscripto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, apellido_nombre VARCHAR(450) DEFAULT \'NULL\' COLLATE utf8_general_ci, dni VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, direccion VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, email VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, telefono VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, sexo VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, fecha_nac DATE DEFAULT \'NULL\', estado INT DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE requisito (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, estado INT DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, codigo LONGTEXT DEFAULT NULL COLLATE utf8_general_ci, estado INT DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tipo_certificado (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, estado INT DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'	\' ');
        $this->addSql('CREATE TABLE tipo_evento (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, estado INT DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE certificado ADD CONSTRAINT fk_certificados_tipo_certificado FOREIGN KEY (tipo_certificado_id) REFERENCES tipo_certificado (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE certificado_evento ADD CONSTRAINT fk_certificado_evento_certificado1 FOREIGN KEY (certificado_id) REFERENCES certificado (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE certificado_evento ADD CONSTRAINT fk_certificado_evento_evento1 FOREIGN KEY (evento_id) REFERENCES evento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE certificado_evento ADD CONSTRAINT fk_certificado_evento_template1 FOREIGN KEY (template_id) REFERENCES template (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE certificado_evento_requisito ADD CONSTRAINT fk_certificado_evento_requisito_certificado_evento1 FOREIGN KEY (certificado_evento_id) REFERENCES certificado_evento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE certificado_evento_requisito ADD CONSTRAINT fk_evento_requisito_requisito1 FOREIGN KEY (requisito_id) REFERENCES requisito (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT fk_evento_tipo_evento1 FOREIGN KEY (tipo_evento_id) REFERENCES tipo_evento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscripto ADD CONSTRAINT fk_inscripto_evento1 FOREIGN KEY (evento_id) REFERENCES evento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscripto ADD CONSTRAINT fk_inscripto_persona1 FOREIGN KEY (persona_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscripto_certificado ADD CONSTRAINT fk_inscripto_certificado_certificado_evento1 FOREIGN KEY (certificado_evento_id) REFERENCES certificado_evento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscripto_certificado ADD CONSTRAINT fk_inscripto_certificado_inscripto1 FOREIGN KEY (inscripto_id) REFERENCES inscripto (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscripto_evento_requisito ADD CONSTRAINT fk_inscripto_evento_requisito_certificado_evento_requisito1 FOREIGN KEY (certificado_evento_requisito_id) REFERENCES certificado_evento_requisito (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscripto_evento_requisito ADD CONSTRAINT fk_inscripto_evento_requisito_inscripto1 FOREIGN KEY (inscripto_id) REFERENCES inscripto (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE fos_user_user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\', CHANGE date_of_birth date_of_birth DATETIME DEFAULT \'NULL\', CHANGE firstname firstname VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE lastname lastname VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE website website VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE biography biography VARCHAR(1000) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gender gender VARCHAR(1) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE locale locale VARCHAR(8) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE timezone timezone VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE phone phone VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE facebook_uid facebook_uid VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE facebook_name facebook_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE facebook_data facebook_data LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', CHANGE twitter_uid twitter_uid VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE twitter_name twitter_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE twitter_data twitter_data LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', CHANGE gplus_uid gplus_uid VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gplus_name gplus_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gplus_data gplus_data LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', CHANGE token token VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE two_step_code two_step_code VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
