-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Nov-2022 às 08:46
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `oficinamecanica`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(14) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `telefone` varchar(13) NOT NULL,
  `celular` varchar(13) NOT NULL,
  `whatsapp` varchar(13) NOT NULL,
  `endereco` varchar(1000) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `bairro` varchar(1000) NOT NULL,
  `cidade` varchar(1000) NOT NULL,
  `uf` char(2) NOT NULL,
  `sexo` char(1) NOT NULL,
  `data_nascimento` varchar(100) NOT NULL,
  `arquivado` tinyint(1) NOT NULL DEFAULT 0,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultima_modificacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` bigint(14) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `cargo` varchar(1000) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `remuneracao` varchar(100) NOT NULL,
  `data_nascimento` varchar(100) NOT NULL,
  `data_admissao` varchar(100) NOT NULL,
  `data_demissao` varchar(100) NOT NULL,
  `arquivado` tinyint(1) NOT NULL DEFAULT 0,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultima_modificacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ordens`
--

CREATE TABLE `ordens` (
  `id` bigint(14) NOT NULL,
  `veiculo_id` bigint(14) NOT NULL,
  `responsavel_id` bigint(14) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 = Orçamento\r\n1= Em andamento\r\n2 = Validar\r\n3 = Finalizada',
  `veiculo_hodometro` varchar(100) NOT NULL,
  `servico_tipo` varchar(100) NOT NULL,
  `observacoes` text NOT NULL,
  `historico` text NOT NULL,
  `previsao` varchar(100) NOT NULL,
  `finalizada_em` varchar(100) NOT NULL,
  `validada` tinyint(1) NOT NULL DEFAULT 0,
  `validacao_ip` varchar(100) NOT NULL,
  `validacao_link` varchar(1000) DEFAULT NULL,
  `validacao_data` datetime DEFAULT NULL,
  `validacao_whatsapp` varchar(100) NOT NULL,
  `arquivado` tinyint(1) NOT NULL DEFAULT 0,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultima_modificacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ordens_fotos`
--

CREATE TABLE `ordens_fotos` (
  `id` bigint(14) NOT NULL,
  `filename` varchar(1000) NOT NULL,
  `extension` char(4) NOT NULL,
  `ordem_id` bigint(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ordens_pecas`
--

CREATE TABLE `ordens_pecas` (
  `id` bigint(14) NOT NULL,
  `descricao` varchar(1000) NOT NULL,
  `qtde` char(2) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `ordem_id` bigint(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ordens_servicos`
--

CREATE TABLE `ordens_servicos` (
  `id` bigint(14) NOT NULL,
  `descricao` varchar(1000) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `ordem_id` bigint(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(14) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `login` varchar(10000) NOT NULL,
  `senha` varchar(1000) NOT NULL,
  `token` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `login`, `senha`, `token`) VALUES
(1, 'Convidado', 'teste_convidado', 'e0f68134d29dc326d115de4c8fab8700a3c4b002', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` bigint(14) NOT NULL,
  `placa` varchar(8) NOT NULL,
  `marca` varchar(1000) NOT NULL,
  `modelo` varchar(1000) NOT NULL,
  `cor` varchar(1000) NOT NULL,
  `ano_fab` int(4) NOT NULL,
  `ano_modelo` int(4) NOT NULL,
  `combustivel` varchar(20) NOT NULL,
  `observacoes` text NOT NULL,
  `arquivado` tinyint(1) NOT NULL DEFAULT 0,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultima_modificacao` datetime NOT NULL DEFAULT current_timestamp(),
  `proprietario_id` bigint(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices para tabela `ordens`
--
ALTER TABLE `ordens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `validacao_link` (`validacao_link`) USING HASH,
  ADD KEY `cliente_id` (`veiculo_id`,`responsavel_id`);

--
-- Índices para tabela `ordens_fotos`
--
ALTER TABLE `ordens_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_id` (`ordem_id`);

--
-- Índices para tabela `ordens_pecas`
--
ALTER TABLE `ordens_pecas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_id` (`ordem_id`);

--
-- Índices para tabela `ordens_servicos`
--
ALTER TABLE `ordens_servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_id` (`ordem_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`) USING HASH;

--
-- Índices para tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `proprietario_id` (`proprietario_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ordens`
--
ALTER TABLE `ordens`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ordens_fotos`
--
ALTER TABLE `ordens_fotos`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ordens_pecas`
--
ALTER TABLE `ordens_pecas`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ordens_servicos`
--
ALTER TABLE `ordens_servicos`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
