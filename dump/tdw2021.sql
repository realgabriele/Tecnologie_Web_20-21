-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Gen 27, 2021 alle 12:35
-- Versione del server: 8.0.22-0ubuntu0.20.04.3
-- Versione PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tdw2021`
--
CREATE DATABASE IF NOT EXISTS `tdw2021` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tdw2021`;

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

DROP TABLE IF EXISTS `articoli`;
CREATE TABLE `articoli` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `descrizione_lunga` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '../assets/na.png',
  `prezzo` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id`, `nome`, `descrizione`, `descrizione_lunga`, `foto`, `prezzo`) VALUES
(1, 'Mascherina Chirurgica', 'mascherina chirurgica bellissima', 'Una maschera (o mascherina) chirurgica, nota anche come maschera medica,\r\n                            o maschera facciale per uso medico,o mascherina igienica (soprattutto tra gli italiani svizzeri),\r\n                            è un dispositivo destinato a essere indossato dagli operatori sanitari durante un intervento chirurgico\r\n                            o altre attività in ambito sanitario al fine di evitare la dispersione di agenti patogeni.', 'https://i.postimg.cc/kDP21W0R/DEFDEF.png', 0.5),
(2, 'Amuchina Mani', 'Gel disinfettante mani', 'Amuchina Gel X-GERM Disinfettante Mani è un gel antisettico, studiato per disinfettare a fondo la pelle delle mani. La sua formulazione è in grado di ridurre efficacemente in pochi secondi germi e batteri presenti sulla cute. Amuchina Gel X-GERM Disinfettante Mani è attivo su virus, funghi e batteri.', 'https://i.postimg.cc/W4YHndfV/DEFDEF-copia.png', 2.75),
(3, 'prova', NULL, NULL, '../assets/na.png', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo_carrello`
--

DROP TABLE IF EXISTS `articolo_carrello`;
CREATE TABLE `articolo_carrello` (
  `carrello_id` int NOT NULL,
  `articolo_id` int NOT NULL,
  `quantita` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articolo_carrello`
--

INSERT INTO `articolo_carrello` (`carrello_id`, `articolo_id`, `quantita`) VALUES
(1, 1, 21),
(1, 2, 12),
(2, 1, 1),
(2, 2, 4),
(3, 1, 3),
(3, 2, 14),
(3, 3, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo_categoria`
--

DROP TABLE IF EXISTS `articolo_categoria`;
CREATE TABLE `articolo_categoria` (
  `articolo_id` int NOT NULL,
  `categoria_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articolo_categoria`
--

INSERT INTO `articolo_categoria` (`articolo_id`, `categoria_id`) VALUES
(1, 1),
(1, 3),
(2, 2),
(3, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo_ordine`
--

DROP TABLE IF EXISTS `articolo_ordine`;
CREATE TABLE `articolo_ordine` (
  `id` int NOT NULL,
  `ordine_id` int NOT NULL,
  `articolo_id` int NOT NULL,
  `quantita` int DEFAULT NULL,
  `prezzo` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articolo_ordine`
--

INSERT INTO `articolo_ordine` (`id`, `ordine_id`, `articolo_id`, `quantita`, `prezzo`) VALUES
(1, 6, 1, 4, 0.5),
(2, 6, 2, 3, 2.75),
(3, 8, 1, 5, 0.4),
(4, 8, 3, 1, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo_wishlist`
--

DROP TABLE IF EXISTS `articolo_wishlist`;
CREATE TABLE `articolo_wishlist` (
  `articolo_id` int NOT NULL,
  `wishlist_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articolo_wishlist`
--

INSERT INTO `articolo_wishlist` (`articolo_id`, `wishlist_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `categorie`
--

INSERT INTO `categorie` (`id`, `nome`, `descrizione`) VALUES
(1, 'mascherine', NULL),
(2, 'disinfettanti', NULL),
(3, 'DPI', 'Dispositivi di Protezione Individuale');

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppi`
--

DROP TABLE IF EXISTS `gruppi`;
CREATE TABLE `gruppi` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `gruppi`
--

INSERT INTO `gruppi` (`id`, `nome`, `descrizione`) VALUES
(1, 'authenticated', 'generic authenticated user'),
(2, 'admin', 'backoffice administrator'),
(4, 'gestore articoli', 'gestione degli articoli e tabelle associate');

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo_servizio`
--

DROP TABLE IF EXISTS `gruppo_servizio`;
CREATE TABLE `gruppo_servizio` (
  `gruppo_id` int NOT NULL,
  `servizio_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `gruppo_servizio`
--

INSERT INTO `gruppo_servizio` (`gruppo_id`, `servizio_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22),
(2, 23),
(2, 24),
(2, 25),
(2, 26),
(2, 27),
(2, 28),
(2, 29),
(2, 30),
(2, 31),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 37),
(2, 38),
(2, 39),
(2, 40),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 46),
(2, 47),
(2, 48),
(2, 49),
(2, 50),
(2, 51),
(2, 52),
(2, 53),
(2, 54),
(2, 55),
(2, 56),
(2, 57),
(2, 58),
(2, 59),
(2, 60),
(2, 61),
(2, 62),
(2, 63),
(2, 64),
(2, 65),
(2, 66),
(2, 67),
(2, 68),
(2, 69),
(2, 70),
(2, 71),
(4, 19),
(4, 20),
(4, 21),
(4, 22),
(4, 23),
(4, 24),
(4, 25),
(4, 26),
(4, 31),
(4, 32),
(4, 33),
(4, 34),
(4, 71);

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzi`
--

DROP TABLE IF EXISTS `indirizzi`;
CREATE TABLE `indirizzi` (
  `id` int NOT NULL,
  `utente_id` int NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cognome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `via` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citta` varchar(127) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cap` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `indirizzi`
--

INSERT INTO `indirizzi` (`id`, `utente_id`, `alias`, `nome`, `cognome`, `via`, `citta`, `provincia`, `cap`) VALUES
(1, 3, 'casa mia 1', 'Nomignolo', 'Cognomignolo', 'via Fasulla, 123 - sc. B', 'Springfield', 'SP', '50123'),
(2, 1, 'casa altra', 'Carolina', 'Vissuti', 'viale dei Giradini', 'L\'Aquila', 'AQ', '67100'),
(5, 3, 'Nuova casa', 'Amministra', 'Tore', 'via comunale, 4', 'Roma', 'RM', '55001');

-- --------------------------------------------------------

--
-- Struttura della tabella `metodipagamento`
--

DROP TABLE IF EXISTS `metodipagamento`;
CREATE TABLE `metodipagamento` (
  `id` int NOT NULL,
  `utente_id` int NOT NULL,
  `intestatario_carta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_carta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scadenza_carta` date DEFAULT NULL,
  `cvv_carta` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `metodipagamento`
--

INSERT INTO `metodipagamento` (`id`, `utente_id`, `intestatario_carta`, `numero_carta`, `scadenza_carta`, `cvv_carta`) VALUES
(1, 3, 'Administratore', '4537890672066', '2021-08-01', '123'),
(2, 3, 'Lalla Llero', '47820951702', '2020-01-13', '111'),
(3, 3, 'lellle', 'ashkd', '2020-05-18', '123');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

DROP TABLE IF EXISTS `ordini`;
CREATE TABLE `ordini` (
  `id` int NOT NULL,
  `utente_id` int NOT NULL,
  `indirizzo_id` int NOT NULL,
  `metodopagamento_id` int NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `evaso` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ordini`
--

INSERT INTO `ordini` (`id`, `utente_id`, `indirizzo_id`, `metodopagamento_id`, `timestamp`, `evaso`) VALUES
(6, 3, 5, 2, '2020-12-29 10:22:46', 0),
(8, 3, 5, 1, '2020-12-30 09:31:34', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `phpauth_config`
--

DROP TABLE IF EXISTS `phpauth_config`;
CREATE TABLE `phpauth_config` (
  `setting` varchar(100) NOT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `phpauth_config`
--

INSERT INTO `phpauth_config` (`setting`, `value`) VALUES
('allow_concurrent_sessions', '0'),
('attack_mitigation_time', '+30 minutes'),
('attempts_before_ban', '30'),
('attempts_before_verify', '5'),
('bcrypt_cost', '10'),
('cookie_domain', NULL),
('cookie_forget', '+30 minutes'),
('cookie_http', '0'),
('cookie_name', 'phpauth_session_cookie'),
('cookie_path', '/'),
('cookie_remember', '+1 week'),
('cookie_renew', '+5 minutes'),
('cookie_secure', '0'),
('custom_datetime_format', 'Y-m-d H:i'),
('emailmessage_suppress_activation', '0'),
('emailmessage_suppress_reset', '0'),
('mail_charset', 'UTF-8'),
('password_min_score', '0'),
('recaptcha_enabled', '0'),
('recaptcha_secret_key', ''),
('recaptcha_site_key', ''),
('request_key_expiration', '+10 minutes'),
('site_activation_page', 'activate'),
('site_activation_page_append_code', '0'),
('site_email', 'no-reply@phpauth.cuonic.com'),
('site_key', 'fghuior.)/!/jdUkd8s2!7HVHG7777ghg'),
('site_language', 'en_GB'),
('site_name', 'PHPAuth'),
('site_password_reset_page', 'reset'),
('site_password_reset_page_append_code', '0'),
('site_timezone', 'Europe/Paris'),
('site_url', 'https://github.com/PHPAuth/PHPAuth'),
('smtp', '0'),
('smtp_auth', '1'),
('smtp_debug', '0'),
('smtp_host', 'smtp.example.com'),
('smtp_password', 'password'),
('smtp_port', '25'),
('smtp_security', NULL),
('smtp_username', 'email@example.com'),
('table_attempts', 'phpauth_attempts'),
('table_emails_banned', 'phpauth_emails_banned'),
('table_requests', 'phpauth_requests'),
('table_sessions', 'phpauth_sessions'),
('table_translations', 'phpauth_translation_dictionary'),
('table_users', 'utenti'),
('translation_source', 'php'),
('verify_email_max_length', '100'),
('verify_email_min_length', '5'),
('verify_email_use_banlist', '1'),
('verify_password_min_length', '3');

-- --------------------------------------------------------

--
-- Struttura della tabella `phpauth_requests`
--

DROP TABLE IF EXISTS `phpauth_requests`;
CREATE TABLE `phpauth_requests` (
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `token` char(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `expire` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `recensioni`
--

DROP TABLE IF EXISTS `recensioni`;
CREATE TABLE `recensioni` (
  `id` int NOT NULL,
  `utente_id` int NOT NULL,
  `articolo_id` int NOT NULL,
  `titolo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descrizione` text COLLATE utf8mb4_unicode_ci,
  `rating` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `recensioni`
--

INSERT INTO `recensioni` (`id`, `utente_id`, `articolo_id`, `titolo`, `descrizione`, `rating`) VALUES
(1, 1, 1, 'Bellissimo TOP!!', 'mi sono trovato molto bene :)', 5),
(2, 1, 2, 'Non ce niente di meglio', 'questo boccione mi ha salvato la vita', 5),
(3, 2, 1, 'buona qualita', NULL, 4),
(4, 3, 1, 'si e\' rotta subito', 'per fortuna che era monouso', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `servizi`
--

DROP TABLE IF EXISTS `servizi`;
CREATE TABLE `servizi` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `servizi`
--

INSERT INTO `servizi` (`id`, `nome`, `descrizione`) VALUES
(2, 'indirizzi.show', NULL),
(3, 'indirizzi.edit', NULL),
(4, 'indirizzi.create', NULL),
(5, 'indirizzi.delete', NULL),
(6, 'metodipagamento.show', NULL),
(7, 'metodipagamento.edit', NULL),
(8, 'metodipagamento.create', NULL),
(9, 'metodipagamento.delete\r\n', NULL),
(10, 'ordini.create', NULL),
(11, 'ordini.show', NULL),
(12, 'wishlist.show', NULL),
(13, 'wishlist.create', NULL),
(14, 'wishlist.delete', NULL),
(15, 'wishlist.edit', NULL),
(16, 'wishlist.share', NULL),
(17, 'utenti.show', NULL),
(18, 'recensioni.write', NULL),
(19, 'backoffice.articoli.show', NULL),
(20, 'backoffice.articoli.edit', NULL),
(21, 'backoffice.articoli.create', NULL),
(22, 'backoffice.articoli.delete', NULL),
(23, 'backoffice.articolo_categoria.show', NULL),
(24, 'backoffice.articolo_categoria.edit', NULL),
(25, 'backoffice.articolo_categoria.create', NULL),
(26, 'backoffice.articolo_categoria.delete', NULL),
(27, 'backoffice.articolo_ordine.show', NULL),
(28, 'backoffice.articolo_ordine.edit', NULL),
(29, 'backoffice.articolo_ordine.create', NULL),
(30, 'backoffice.articolo_ordine.delete', NULL),
(31, 'backoffice.categorie.show', NULL),
(32, 'backoffice.categorie.edit', NULL),
(33, 'backoffice.categorie.create', NULL),
(34, 'backoffice.categorie.delete', NULL),
(35, 'backoffice.gruppi.show', NULL),
(36, 'backoffice.gruppi.edit', NULL),
(37, 'backoffice.gruppi.create', NULL),
(38, 'backoffice.gruppi.delete', NULL),
(39, 'backoffice.gruppo_servizio.show', NULL),
(40, 'backoffice.gruppo_servizio.edit', NULL),
(41, 'backoffice.gruppo_servizio.create', NULL),
(42, 'backoffice.gruppo_servizio.delete', NULL),
(43, 'backoffice.indirizzi.show', NULL),
(44, 'backoffice.indirizzi.edit', NULL),
(45, 'backoffice.indirizzi.create', NULL),
(46, 'backoffice.indirizzi.delete', NULL),
(47, 'backoffice.metodipagamento.show', NULL),
(48, 'backoffice.metodipagamento.edit', NULL),
(49, 'backoffice.metodipagamento.create', NULL),
(50, 'backoffice.metodipagamento.delete', NULL),
(51, 'backoffice.recensioni.show', NULL),
(52, 'backoffice.recensioni.edit', NULL),
(53, 'backoffice.recensioni.create', NULL),
(54, 'backoffice.recensioni.delete', NULL),
(55, 'backoffice.ordini.show', NULL),
(56, 'backoffice.ordini.edit', NULL),
(57, 'backoffice.ordini.create', NULL),
(58, 'backoffice.ordini.delete', NULL),
(59, 'backoffice.utente_gruppo.show', NULL),
(60, 'backoffice.utente_gruppo.edit', NULL),
(61, 'backoffice.utente_gruppo.create', NULL),
(62, 'backoffice.utente_gruppo.delete', NULL),
(63, 'backoffice.servizi.show', NULL),
(64, 'backoffice.servizi.edit', NULL),
(65, 'backoffice.servizi.create', NULL),
(66, 'backoffice.servizi.delete', NULL),
(67, 'backoffice.utenti.show', NULL),
(68, 'backoffice.utenti.edit', NULL),
(69, 'backoffice.utenti.create', NULL),
(70, 'backoffice.utenti.delete', NULL),
(71, 'backoffice', 'accesso al backoffice');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente_gruppo`
--

DROP TABLE IF EXISTS `utente_gruppo`;
CREATE TABLE `utente_gruppo` (
  `utente_id` int NOT NULL,
  `gruppo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utente_gruppo`
--

INSERT INTO `utente_gruppo` (`utente_id`, `gruppo_id`) VALUES
(3, 1),
(3, 2),
(3, 4),
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `email`, `password`) VALUES
(1, 'Giovanni Spada', 'giavannino@gmail.com', '$2y$10$EAhLhUi0SsKAa5/shXAnXOHePRVmGjvOjp7UYLERqDC1xI5BBaso2'),
(2, 'Rita Calamita', 'Xx_rita_xX@yahoo.it', 'Questa Psw deve essere un hash'),
(3, 'admin', 'admin@admin.net', '$2y$10$iJcIRXGq21lUfMBwiXrrYevzrRy5WjXlve2QdGM2Gx2zbqjBZFUxa'),
(4, 'aaa', 'aaa@email.it', '$2y$10$r/Y1zZk7cgI5LpD8njh8reG/ulE66mP1HviblPKpcBEeKBK.vPJbK');

-- --------------------------------------------------------

--
-- Struttura della tabella `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `id` int NOT NULL,
  `utente_id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `wishlist`
--

INSERT INTO `wishlist` (`id`, `utente_id`, `nome`) VALUES
(1, 3, 'mia lista desideri'),
(5, 3, 'lallallereo');

-- --------------------------------------------------------

--
-- Struttura della tabella `wishlist_condivisione`
--

DROP TABLE IF EXISTS `wishlist_condivisione`;
CREATE TABLE `wishlist_condivisione` (
  `wishlist_id` int NOT NULL,
  `utente_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `wishlist_condivisione`
--

INSERT INTO `wishlist_condivisione` (`wishlist_id`, `utente_id`) VALUES
(1, 4),
(5, 4);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `articolo_carrello`
--
ALTER TABLE `articolo_carrello`
  ADD PRIMARY KEY (`carrello_id`,`articolo_id`);

--
-- Indici per le tabelle `articolo_categoria`
--
ALTER TABLE `articolo_categoria`
  ADD PRIMARY KEY (`articolo_id`,`categoria_id`);

--
-- Indici per le tabelle `articolo_ordine`
--
ALTER TABLE `articolo_ordine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ordine_id` (`ordine_id`,`articolo_id`);

--
-- Indici per le tabelle `articolo_wishlist`
--
ALTER TABLE `articolo_wishlist`
  ADD PRIMARY KEY (`articolo_id`,`wishlist_id`);

--
-- Indici per le tabelle `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `gruppi`
--
ALTER TABLE `gruppi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `gruppo_servizio`
--
ALTER TABLE `gruppo_servizio`
  ADD PRIMARY KEY (`gruppo_id`,`servizio_id`);

--
-- Indici per le tabelle `indirizzi`
--
ALTER TABLE `indirizzi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `metodipagamento`
--
ALTER TABLE `metodipagamento`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `phpauth_config`
--
ALTER TABLE `phpauth_config`
  ADD UNIQUE KEY `setting` (`setting`);

--
-- Indici per le tabelle `phpauth_requests`
--
ALTER TABLE `phpauth_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`),
  ADD KEY `uid` (`uid`);

--
-- Indici per le tabelle `recensioni`
--
ALTER TABLE `recensioni`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `utente_id` (`utente_id`,`articolo_id`);

--
-- Indici per le tabelle `servizi`
--
ALTER TABLE `servizi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `utente_gruppo`
--
ALTER TABLE `utente_gruppo`
  ADD PRIMARY KEY (`utente_id`,`gruppo_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `wishlist_condivisione`
--
ALTER TABLE `wishlist_condivisione`
  ADD PRIMARY KEY (`wishlist_id`,`utente_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `articoli`
--
ALTER TABLE `articoli`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `articolo_ordine`
--
ALTER TABLE `articolo_ordine`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `gruppi`
--
ALTER TABLE `gruppi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `indirizzi`
--
ALTER TABLE `indirizzi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `metodipagamento`
--
ALTER TABLE `metodipagamento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ordini`
--
ALTER TABLE `ordini`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `phpauth_requests`
--
ALTER TABLE `phpauth_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `servizi`
--
ALTER TABLE `servizi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
