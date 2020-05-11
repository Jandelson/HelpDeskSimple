create database helpdeskDB;
use helpdeskDB;

CREATE TABLE `usuario` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `PKusuario` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `licencas` (
    `id_licencas` INT(11) NOT NULL DEFAULT '0',
    `id_cliente` INT(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id_cliente`)
)  ENGINE=INNODB DEFAULT CHARSET=LATIN1;

CREATE TABLE `cliente` (
    `id_cliente` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(80) DEFAULT NULL,
    `endereco` VARCHAR(50) DEFAULT NULL,
    `cep` VARCHAR(9) DEFAULT NULL,
    `cidade` VARCHAR(35) NOT NULL DEFAULT '',
    `estado` CHAR(2) DEFAULT NULL,
    `identificacao` VARCHAR(20) DEFAULT NULL,
    `contato` VARCHAR(30) DEFAULT NULL,
	`observacao` MEDIUMTEXT NOT NULL,
    PRIMARY KEY (`id_cliente`)
)  ENGINE=INNODB DEFAULT CHARSET=LATIN1;

CREATE TABLE `helpdesk_status` (
    `id_helpdesk_status` INT(11) NOT NULL,
    `descricao` VARCHAR(30) DEFAULT NULL,
    `cor` VARCHAR(15) DEFAULT NULL,
    PRIMARY KEY (`id_helpdesk_status`)
)  ENGINE=INNODB DEFAULT CHARSET=LATIN1;

CREATE TABLE `chamado` (
    `id_chamado` INT(11) NOT NULL AUTO_INCREMENT,
    `id_usuario` INT(11) NOT NULL DEFAULT '0',
    `id_usuario_responsavel` INT(11) NOT NULL DEFAULT '0',
    `id_cliente` INT(11) NOT NULL DEFAULT '0',
    `data` DATE NOT NULL DEFAULT '0000-00-00',
    `data_previsao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `data_conclusao` DATE NOT NULL DEFAULT '0000-00-00',
    `solicitante` VARCHAR(40) NOT NULL,
    `descricao` MEDIUMTEXT NOT NULL,
    `status` TINYINT(4) NOT NULL DEFAULT '0',
    `recorrente` TINYINT(4) NOT NULL DEFAULT '0',
    `data_hora_criacao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `data_hora_alteracao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `data_previsao_atendimento` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `prioridade` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0-Baixa, 9-Alta',
    `id_usuario_helpdesk` VARCHAR(255) DEFAULT NULL COMMENT 'chave que grava o id do usuario composto por id e cnpj da empresa',
    `id_usuario_cliente` INT(11) DEFAULT NULL,
    `titulo` MEDIUMTEXT COMMENT 'titulo da pendÃªncia do usuario',
    `id_tipo_chamado` INT(11) DEFAULT NULL,
    `email_helpdesk` VARCHAR(200) DEFAULT NULL,
    PRIMARY KEY (`id_chamado`)
)  ENGINE=INNODB DEFAULT CHARSET=LATIN1;

CREATE TABLE `tipo_chamado` (
    `id_tipo_chamado` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `descricao` VARCHAR(50) DEFAULT NULL,
    `cor_tipo_chamado` VARCHAR(15) NOT NULL DEFAULT '',
    PRIMARY KEY (`id_tipo_chamado`)
)  ENGINE=INNODB DEFAULT CHARSET=LATIN1;

CREATE TABLE `chamado_log` (
    `id_chamado_log` INT(11) NOT NULL AUTO_INCREMENT,
    `id_usuario` INT(11) NOT NULL DEFAULT '0',
    `data_hora` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `descricao` MEDIUMTEXT NOT NULL,
    `status` TINYINT(4) NOT NULL DEFAULT '0',
    `id_chamado` INT(11) NOT NULL DEFAULT '0',
    `hora_final` VARCHAR(5) DEFAULT NULL,
    `id_cliente` INT(11) NOT NULL DEFAULT '0',
    `id_usuario_cliente` INT(11) DEFAULT NULL,
    `visivel_cliente` TINYINT(4) DEFAULT '1' COMMENT 'marca flag se essa interação da pendência vai poder ser visivel pelo cliente',
    PRIMARY KEY (`id_chamado_log`)
)  ENGINE=INNODB AUTO_INCREMENT=6 DEFAULT CHARSET=LATIN1;

CREATE TABLE `documento` (
    `id_documento` INT(11) NOT NULL AUTO_INCREMENT,
    `id_chamado` INT(11) NOT NULL DEFAULT '0',
    `nome` VARCHAR(100) NOT NULL DEFAULT '',
    `data_upload` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `id_usuario` INT(11) NOT NULL DEFAULT '0',
    `descricao` VARCHAR(200) DEFAULT NULL,
    PRIMARY KEY (`id_documento`)
)  ENGINE=INNODB DEFAULT CHARSET=LATIN1;