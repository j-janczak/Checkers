-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Sty 2019, 14:05
-- Wersja serwera: 10.1.28-MariaDB
-- Wersja PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `game_class`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `first_player` int(11) NOT NULL,
  `second_player` int(11) NOT NULL,
  `map` text COLLATE utf8_polish_ci NOT NULL,
  `active_time` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `games`
--

INSERT INTO `games` (`id`, `status`, `first_player`, `second_player`, `map`, `active_time`) VALUES
(23, 1, 11, 10, '[[0,1,0,0,0,2,0,2],[1,0,1,0,0,0,2,0],[0,1,0,0,0,2,0,2],[1,0,1,0,0,0,2,0],[0,1,0,0,0,2,0,2],[1,0,1,0,0,0,2,0],[0,1,0,0,0,2,0,2],[1,0,1,0,0,0,2,0]]', 1548330651);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL,
  `password` text COLLATE utf8_polish_ci NOT NULL,
  `register_date` text COLLATE utf8_polish_ci NOT NULL,
  `last_seen` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `register_date`, `last_seen`) VALUES
(10, 'TheFlashes', '', '827ccb0eea8a706c4c34a16891f84e7b', '2018-12-13 12:07:02', 1548335144),
(11, 'qwerty', '', '827ccb0eea8a706c4c34a16891f84e7b', '2018-12-13 12:07:35', 1548335144),
(12, 'Kuba', '', '827ccb0eea8a706c4c34a16891f84e7b', '2018-12-14 09:58:12', 1546601347);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
