-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Окт 24 2014 г., 13:15
-- Версия сервера: 5.5.32
-- Версия PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `survey`
--
CREATE DATABASE IF NOT EXISTS `survey` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `survey`;

-- --------------------------------------------------------

--
-- Структура таблицы `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

--
-- Дамп данных таблицы `answer`
--

INSERT INTO `answer` (`id`, `title`, `question_id`) VALUES
(14, 'Это мой первый визит', 5),
(15, 'Раз в месяц и реже', 5),
(16, 'Несколько раз в месяц', 5),
(17, 'Новости', 6),
(18, 'О компании', 6),
(19, 'Производство', 6),
(20, 'Контакты', 6),
(21, 'Мужской', 7),
(22, 'Женский', 7),
(23, 'Меньше 20', 8),
(24, '20-30 лет', 8),
(25, '31-40 лет', 8),
(26, 'Старше 40', 8),
(27, 'Windows', 9),
(28, 'Linux', 9),
(29, 'Mac OS', 9);

-- --------------------------------------------------------

--
-- Структура таблицы `poll`
--

CREATE TABLE IF NOT EXISTS `poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','draft','archived','') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `poll`
--

INSERT INTO `poll` (`id`, `title`, `status`) VALUES
(1, 'Исследование аудитории сайта', 'active'),
(2, 'Операционные системы', 'draft');

-- --------------------------------------------------------

--
-- Структура таблицы `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_multiple` tinyint(1) DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `poll_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `question`
--

INSERT INTO `question` (`id`, `title`, `is_multiple`, `is_required`, `poll_id`) VALUES
(5, 'Как часто Вы заходите на сайт?', 0, 0, 1),
(6, 'Какие разделы представляют для Вас наибольший интерес?', 1, 1, 1),
(7, 'Ваш пол', 0, 1, 1),
(8, 'Ваш возраст', 0, 0, 1),
(9, 'Какой операционной системой Вы пользуетесь?', 1, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answer_id` int(11) NOT NULL,
  `user_sign` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_id` (`answer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Дамп данных таблицы `vote`
--

INSERT INTO `vote` (`id`, `answer_id`, `user_sign`) VALUES
(1, 14, '544a342a5080d'),
(2, 18, '544a342a5080d'),
(3, 22, '544a342a5080d'),
(4, 25, '544a342a5080d'),
(5, 15, '544a343ba99ce'),
(6, 19, '544a343ba99ce'),
(7, 20, '544a343ba99ce'),
(8, 21, '544a343ba99ce'),
(9, 24, '544a343ba99ce'),
(10, 18, '544a3443841ff'),
(11, 19, '544a3443841ff'),
(12, 21, '544a3443841ff'),
(13, 16, '544a34594205a'),
(14, 17, '544a34594205a'),
(15, 19, '544a34594205a'),
(16, 20, '544a34594205a'),
(17, 22, '544a34594205a'),
(18, 24, '544a34594205a'),
(19, 19, '544a34622d650'),
(20, 21, '544a34622d650'),
(21, 18, '544a3475c4caa'),
(22, 20, '544a3475c4caa'),
(23, 21, '544a3475c4caa'),
(24, 25, '544a3475c4caa'),
(25, 14, '544a348fb5876'),
(26, 19, '544a348fb5876'),
(27, 22, '544a348fb5876'),
(28, 26, '544a348fb5876');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
