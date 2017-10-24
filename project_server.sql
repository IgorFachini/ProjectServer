-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 24-Out-2017 às 12:46
-- Versão do servidor: 5.6.35-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uaudeco_project`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `feed`
--

CREATE TABLE `feed` (
  `feed_id` int(11) NOT NULL,
  `feed` text,
  `user_id_fk` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `feed`
--

INSERT INTO `feed` (`feed_id`, `feed`, `user_id_fk`, `created`) VALUES
(1, 'as', 1, 1503506110),
(2, 'asa', 1, 1503672236),
(3, 'as', 2, 1503672307),
(4, 'asa', 1, 1503672372),
(5, 'as', 1, 1503672380),
(6, 'as', 1, 1503672387),
(7, '1', 1, 1503672397),
(8, '1', 1, 1503672425);

-- --------------------------------------------------------

--
-- Estrutura da tabela `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `userClient_id` int(11) DEFAULT NULL,
  `userEmployee_id` int(11) DEFAULT NULL,
  `createdHour` varchar(20) DEFAULT NULL,
  `latLng` varchar(80) DEFAULT NULL,
  `timeSevered` varchar(20) DEFAULT '0',
  `finishedTime` varchar(20) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `orders`
--

INSERT INTO `orders` (`id`, `description`, `userClient_id`, `userEmployee_id`, `createdHour`, `latLng`, `timeSevered`, `finishedTime`) VALUES
(60, '', 29, 5, '1505495963', '{\"Lat\":-26.4638147,\"Lng\":-49.11669029999996}', '1505496270', '0'),
(62, '', 30, 5, '1508498231', '{\"Lat\":-26.3045084,\"Lng\":-48.846383200000005}', '1505505502', '1505505502'),
(63, 'casa rosa', 30, 24, '1506528176', '{\"Lat\":-26.3084084,\"Lng\":-48.854383200000005}', '1506528241', '1506528278'),
(64, '', 31, 5, '1509537798', '{\"Lat\":-26.4634147,\"Lng\":-49.12469029999996}', '1507053987', '0'),
(65, 'Tt', 32, 0, '1506964106', '{\"Lat\":-26.45238359999999,\"Lng\":-49.16334189999998}', '0', '0'),
(67, '', 30, 0, '1507055404', '{\"Lat\":-26.3044084,\"Lng\":-48.846383200000005}', '0', '0'),
(68, '', 27, 0, '1507056350', '{\"Lat\":0,\"Lng\":0}', '0', '0');

-- --------------------------------------------------------

--
-- Estrutura da tabela `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `price` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `product`
--

INSERT INTO `product` (`id`, `title`, `description`, `stock`, `price`) VALUES
(1, 'P2 - BOTIJÃO PORTÁTIL', '', 0, 22),
(2, 'P5 - BOTIJÃO RESIDENCIAL PEQUENO', '', 0, 50),
(5, 'P13 - BOTIJÃO RESIDENCIAL PADRÃO', 'O botijão de gás de 13 quilos, também conhecido como botijão de gás P13 é o gás de cozinha comum amplamente utilizado nos fogões residenciais em todo país.', 0, 60),
(6, 'P20 - GÁS PARA EMPILHADEIRAS', 'O gás para empilhadeiras ou gás P20 é comercializado em cilindros de gás de 20 quilos. ', 0, 80),
(7, 'P45 - BOTIJÃO DE LARGA ESCALA', 'O cilindro de gás P45 possui 45 quilos.', 0, 100),
(8, 'P90 - BOTIJÃO DE MAIOR VOLUME', 'O cilindro de gás possui 90 quilos ou gás P90, é empregado por consumidores que precisam de um maior volume de gás, como nos segmentos comerciais, industriais e empresariais.', 0, 120);

-- --------------------------------------------------------

--
-- Estrutura da tabela `productOrder`
--

CREATE TABLE `productOrder` (
  `id` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `productOrder`
--

INSERT INTO `productOrder` (`id`, `orderId`, `productId`, `amount`) VALUES
(48, 60, 1, 2),
(49, 60, 2, 1),
(51, 62, 2, 1),
(52, 63, 1, 2),
(53, 64, 7, 2),
(54, 65, 1, 3),
(56, 67, 1, 1),
(57, 68, 5, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `phone` int(11) NOT NULL,
  `userType` varchar(10) NOT NULL,
  `permissionType` int(11) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  `latLng` varchar(80) DEFAULT NULL,
  `complement` varchar(50) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `cnpj` varchar(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `name`, `email`, `phone`, `userType`, `permissionType`, `address`, `latLng`, `complement`, `cpf`, `cnpj`) VALUES
(5, 'fc1', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Thesco', 'thesco@gmail.com', 11223344, 'Employee', 1, '0', '{\"Lat\":0,\"Lng\":0}', '', '500.111.157-93', ''),
(24, 'fc2', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Igor', 'igor@gmail.com', 0, 'Employee', 1, '0', '0', '', NULL, NULL),
(27, 'cl1', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'Ana', 'cl12@t.com', 11223344, 'Client', 0, 'Vila Nova, Jaraguá do Sul - SC, 89254-700, Brasil', '{\"Lat\":0,\"Lng\":0}', 'apa', '095.432.389-01', ''),
(28, 'teste', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'teste', 'teste@teste.com', 0, 'Client', 0, 'Av. Juscelino Kubitscheck, 350 - Centro, Joinville', '{\"Lat\":-26.3044084,\"Lng\":-48.846383200000005}', 'casa', '', '91.358.419/0001-75'),
(29, 'cl2', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'Carioca', 'cl2@t.com', 11223345, 'Client', 0, 'Rau, Jaraguá do Sul - SC, 89254-700, Brasil', '{\"Lat\":-26.4638147,\"Lng\":-49.11669029999996}', 'casa', '161.783.840-33', ''),
(30, 'cl3', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'Maria', 'cl3@t.com', 123313123, 'Client', 0, 'Av. Juscelino Kubitscheck, 350 - Centro, Joinville', '{\"Lat\":-26.3044084,\"Lng\":-48.846383200000005}', 'casa', '947.797.740-39', ''),
(31, 'cl4', 'fbfb386efea67e816f2dda0a8c94a98eb203757aebb3f55f183755a192d44467', 'Paulo Silva', 'cl4@t.com', 11224455, 'Client', 0, 'Rau, Jaraguá do Sul - SC, 89254-700, Brazil', '{\"Lat\":-26.4638147,\"Lng\":-49.11669029999996}', '', '291.167.710-28', ''),
(32, 'cl5', 'fbfb386efea67e816f2dda0a8c94a98eb203757aebb3f55f183755a192d44467', 'Flavio', 'cl5@t.com', 11223344, 'Client', 0, 'R. Luiz Sarti, 761 - Nereu Ramos, Jaraguá do Sul -', '{\"Lat\":-26.45238359999999,\"Lng\":-49.16334189999998}', 'Casa verde', '', '49.843.741/870-');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feed`
--
ALTER TABLE `feed`
  ADD PRIMARY KEY (`feed_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userClient_id` (`userClient_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productOrder`
--
ALTER TABLE `productOrder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feed`
--
ALTER TABLE `feed`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `productOrder`
--
ALTER TABLE `productOrder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userClient_id`) REFERENCES `users` (`user_id`);

--
-- Limitadores para a tabela `productOrder`
--
ALTER TABLE `productOrder`
  ADD CONSTRAINT `productOrder_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `productOrder_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
