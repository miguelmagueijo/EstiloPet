-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Maio-2023 às 20:35
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lpi`
--
CREATE DATABASE IF NOT EXISTS `lpi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `lpi`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal`
--

DROP TABLE IF EXISTS `animal`;
CREATE TABLE `animal` (
  `idAnimal` int(11) NOT NULL,
  `nomeAnimal` varchar(30) NOT NULL,
  `porte` varchar(10) NOT NULL,
  `tipoAnimal` varchar(5) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `animal`
--

INSERT INTO `animal` (`idAnimal`, `nomeAnimal`, `porte`, `tipoAnimal`, `idUser`) VALUES
(16, 'Xica', 'medio', 'gato', 7),
(19, 'Zé', 'pequeno', 'gato', 7),
(22, 'Tobby', 'grande', 'cao', 7),
(42, 'Snoopy', 'Pequeno', 'Cão', 1),
(43, 'Rex', 'Grande', 'Cão', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcacoes`
--

DROP TABLE IF EXISTS `marcacoes`;
CREATE TABLE `marcacoes` (
  `idMarcacao` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `idUser` int(11) NOT NULL,
  `idAnimal` int(11) NOT NULL,
  `tratamento` varchar(10) NOT NULL,
  `func` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos_func`
--

DROP TABLE IF EXISTS `servicos_func`;
CREATE TABLE `servicos_func` (
  `idUser` int(11) NOT NULL,
  `tratamento` varchar(10) NOT NULL,
  `tipoAnimal` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servicos_func`
--

INSERT INTO `servicos_func` (`idUser`, `tratamento`, `tipoAnimal`) VALUES
(3, 'banho', 'cao'),
(3, 'banho', 'gato'),
(3, 'corte', 'cao'),
(3, 'corte', 'gato'),
(4, 'banho', 'cao'),
(4, 'banho', 'gato'),
(4, 'corte', 'gato'),
(5, 'banho', 'cao'),
(5, 'banho', 'gato');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `nomeUser` varchar(50) NOT NULL,
  `morada` varchar(60) NOT NULL,
  `email` varchar(30) NOT NULL,
  `telemovel` int(11) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `tipoUtilizador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`idUser`, `nomeUser`, `morada`, `email`, `telemovel`, `pass`, `tipoUtilizador`) VALUES
(1, 'cliente', 'Rua do lado', 'cliente@cliente.pt', 912345678, '4983a0ab83ed86e0e7213c8783940193', 2),
(3, 'joao', 'Rua da direita', 'joao@joao.pt', 222723687, 'dccd96c256bc7dd39bae41a405f25e43', 1),
(4, 'joana', 'Rua da esquerda', 'joana@joana.pt', 999333444, '18f01959ff46071d73905d549cafde20', 1),
(5, 'maria', 'Rua de cima', 'maria@maria.pt', 923355771, '263bce650e68ab4e23f28263760b9fa5', 1),
(6, 'admin', 'Rua de baixo ', 'admi@admin.pt', 999999999, '21232f297a57a5a743894a0e4a801fc3', 0),
(7, 'teste', 'rua do teste', 'teste@teste.pt', 919191919, '698dc19d489c4e4db73e28a713eab07b', 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`idAnimal`),
  ADD KEY `fx_animal_user` (`idUser`);

--
-- Índices para tabela `marcacoes`
--
ALTER TABLE `marcacoes`
  ADD PRIMARY KEY (`data`,`hora`,`idUser`,`idAnimal`,`tratamento`,`func`) USING BTREE,
  ADD KEY `fk_animal_marcacao` (`idAnimal`),
  ADD KEY `func` (`func`),
  ADD KEY `fk_user_marcacao` (`idUser`);

--
-- Índices para tabela `servicos_func`
--
ALTER TABLE `servicos_func`
  ADD PRIMARY KEY (`idUser`,`tratamento`,`tipoAnimal`) USING BTREE;

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `nomeUser` (`nomeUser`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `animal`
--
ALTER TABLE `animal`
  MODIFY `idAnimal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `fk_animal_utilizador` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `marcacoes`
--
ALTER TABLE `marcacoes`
  ADD CONSTRAINT `fk_animal_marcacao` FOREIGN KEY (`idAnimal`) REFERENCES `animal` (`idAnimal`),
  ADD CONSTRAINT `marcacoes_ibfk_1` FOREIGN KEY (`func`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `marcacoes_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `servicos_func`
--
ALTER TABLE `servicos_func`
  ADD CONSTRAINT `servicos_func_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
