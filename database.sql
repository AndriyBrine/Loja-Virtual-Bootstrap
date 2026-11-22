/* 
SCRIPT DE GERAÇÃO DO BANCO DE DADOS 
*/
-- Criando o banco de dados
CREATE DATABASE IF NOT EXISTS `andriy_loja_vendas`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

-- Selecionando o banco de dados
USE `andriy_loja_vendas`;

-- Criando a tabela de produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `descricao` TEXT NULL DEFAULT NULL,
  `imagem` VARCHAR(255) NULL DEFAULT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `estoque` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)