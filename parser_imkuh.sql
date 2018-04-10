-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 10 2018 г., 15:10
-- Версия сервера: 5.7.21-0ubuntu0.16.04.1
-- Версия PHP: 7.0.29-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `parser_imkuh`
--

-- --------------------------------------------------------

--
-- Структура таблицы `curl`
--

CREATE TABLE `curl` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` text,
  `value` text,
  `sites_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `curl`
--

INSERT INTO `curl` (`id`, `key`, `value`, `sites_id`) VALUES
(3, 'CURLOPT_REFERER', 'https://dealer.equip.center/personal/', 4),
(4, 'CURLOPT_POST', '0', 4),
(5, 'CURLOPT_USERAGENT', 'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4', 4),
(6, 'CURLOPT_RETURNTRANSFER', '1', 4),
(7, 'CURLOPT_COOKIEFILE', 'cookie-dealer', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `curl_auth`
--

CREATE TABLE `curl_auth` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` text,
  `value` text,
  `sites_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `curl_auth`
--

INSERT INTO `curl_auth` (`id`, `key`, `value`, `sites_id`) VALUES
(1, 'CURLOPT_URL', 'https://dealer.equip.center/personal/?login=yes', 4),
(2, 'CURLOPT_REFERER', 'https://dealer.equip.center/personal/', 4),
(3, 'CURLOPT_POST', '1', 4),
(4, 'CURLOPT_POSTFIELDS', 'AUTH_FORM=>Y,TYPE=>AUTH,backurl=>/personal/,USER_LOGIN=>info@imkuh.ru,USER_PASSWORD=>info@imkuh.ru,Login=>Войти', 4),
(5, 'CURLOPT_USERAGENT', 'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4', 4),
(6, 'CURLOPT_RETURNTRANSFER', '1', 4),
(7, 'CURLOPT_COOKIEJAR', 'cookie-dealer', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `description`
--

CREATE TABLE `description` (
  `id` int(10) UNSIGNED NOT NULL,
  `main` text,
  `additional` text,
  `goods_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(10) UNSIGNED NOT NULL,
  `name_goods` varchar(255) DEFAULT NULL,
  `uri_goods` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sites_id` int(10) UNSIGNED NOT NULL,
  `groups_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `url_group` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `goods_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text,
  `description` text,
  `desc_add` text,
  `image` text,
  `price` text,
  `manufactured` text,
  `prod_attr` text,
  `goods_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `logs_curl`
--

CREATE TABLE `logs_curl` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text,
  `goods_id` int(11) DEFAULT NULL,
  `sites_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `goods_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `name_regular`
--

CREATE TABLE `name_regular` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `name_regular`
--

INSERT INTO `name_regular` (`id`, `name`) VALUES
(1, 'Блок для скачивания ссылок'),
(2, 'Название группы'),
(3, 'Ссылки на скачиваемые товары'),
(4, 'Пагинация сайта'),
(5, 'Загрузка имени'),
(6, 'Загрузка описания'),
(7, 'Загрузка дополнительного описания'),
(8, 'Загрузка изображения'),
(9, 'Маркер ссылки на No Foto'),
(10, 'Загрузка цены товара'),
(11, 'Загрузка производителя товара'),
(12, 'Загрузка атрибутов продуктов');

-- --------------------------------------------------------

--
-- Структура таблицы `price`
--

CREATE TABLE `price` (
  `id` int(10) UNSIGNED NOT NULL,
  `price` int(255) DEFAULT NULL,
  `goods_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `content` text,
  `goods_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sites`
--

CREATE TABLE `sites` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `down_url` varchar(255) DEFAULT NULL,
  `queue` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `usleep_start` int(11) DEFAULT '0',
  `usleep_stop` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sites`
--

INSERT INTO `sites` (`id`, `name`, `url`, `down_url`, `queue`, `status`, `usleep_start`, `usleep_stop`) VALUES
(1, 'Gemlux kush ', 'http://gemlux.ru', 'http://gemlux.ru/catalog/household-appliances/', 0, 0, 0, 0),
(2, 'Gemlux comm без серии 700', 'http://gemlux.ru', 'http://gemlux.ru/catalog/commercial/', 0, 0, 0, 0),
(3, 'Gemlux comm с серий 700', 'http://gemlux.ru', 'http://gemlux.ru/catalog/commercial/modular-heating-equipment/series-700/', 0, 0, 0, 0),
(4, 'Эквип-центр', 'https://dealer.equip.center', 'https://dealer.equip.center/', 3, 1, 0, 0),
(5, 'Gastrorag без ножей и кухонной посуды', 'http://gastrorag.ru', 'http://gastrorag.ru/katalog-oborudovaniya/', 0, 0, 0, 0),
(6, 'Gastrorag с ножами', 'http://gastrorag.ru', 'http://gastrorag.ru/katalog-oborudovaniya/Posuda-inventar/nozhi/', 0, 0, 0, 0),
(7, 'Gastrorag с кухонной посудой', 'http://gastrorag.ru', 'http://gastrorag.ru/katalog-oborudovaniya/Posuda-inventar/Kukhonnaya-naplitnaya-posuda/', 0, 0, 0, 0),
(8, 'Центр оборудования', 'http://centr-oborudovaniya.ru', 'http://centr-oborudovaniya.ru/', 3, 0, 1000000, 3000000),
(9, 'Chudokreslo', 'http://www.chudokreslo.com', 'http://www.chudokreslo.com/', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `xpath`
--

CREATE TABLE `xpath` (
  `id` int(10) UNSIGNED NOT NULL,
  `regular` varchar(255) DEFAULT NULL,
  `sites_id` int(10) UNSIGNED NOT NULL,
  `name_regular_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xpath`
--

INSERT INTO `xpath` (`id`, `regular`, `sites_id`, `name_regular_id`) VALUES
(1, '//div[@class=\'wrapper_inner\']//div[@class=\'col-md-3\']//li//a', 1, 1),
(2, '//div[@id=\'h1_and_compare\']//h1', 1, 2),
(3, '//div[@class=\'item-title\']//a/@href', 1, 3),
(4, '?PAGEN_1=', 1, 4),
(5, '//div[@id=\'h1_and_compare\']//h1', 1, 5),
(6, '//div[@class=\'detail_text\']', 1, 6),
(7, '//div[@class=\'preview_text\']', 1, 7),
(8, '//div[@class=\'slides\']//img/@src', 1, 8),
(9, '//table[@class=\'props_list\']', 1, 12),
(10, '//div[@class=\'commercial-block-link\']//li//a[not(@href=\'/catalog/commercial/modular-heating-equipment/series-700/\')]', 2, 1),
(11, '//div[@id=\'h1_and_compare\']//h1', 2, 2),
(12, '//div[@class=\'item-title\']//a/@href', 2, 3),
(13, '?PAGEN_1=', 2, 4),
(14, '//div[@id=\'h1_and_compare\']//h1', 2, 5),
(15, '//div[@class=\'detail_text\']', 2, 6),
(16, '//div[@class=\'preview_text\']', 2, 7),
(17, '//div[@class=\'slides\']//img/@src', 2, 8),
(18, '//table[@class=\'props_list\']', 2, 12),
(19, '//div[@class=\'item_text_block\']//div[@class=\'item-title\']//a', 3, 1),
(20, '//div[@id=\'h1_and_compare\']//h1', 3, 2),
(21, '//div[@class=\'item-title\']//a/@href', 3, 3),
(22, '?PAGEN_1=', 3, 4),
(23, '//div[@id=\'h1_and_compare\']//h1', 3, 5),
(24, '//div[@class=\'detail_text\']', 3, 6),
(25, '//div[@class=\'preview_text\']', 3, 7),
(26, '//div[@class=\'slides\']//img/@src', 3, 8),
(27, '//table[@class=\'props_list\']', 3, 12),
(28, '//section[@id=\'complex_wrap\']//h4//a', 4, 1),
(29, '//h1[@class=\'categroy-panel-h1\']', 4, 2),
(30, '//div[@class=\'product_title\']//a/@href', 4, 3),
(31, '?PAGE=', 4, 4),
(32, '//h1[@class=\'detail\']', 4, 5),
(33, '//div[@id=\'advantages\']', 4, 6),
(34, '//div[@id=\'technical\']', 4, 7),
(35, '//div[@class=\'item active\']//img/@src', 4, 8),
(36, '/bitrix/templates/dealer.equip.center/img/nopicture200.png', 4, 9),
(37, '//div[@id=\'product_desc\']', 4, 12),
(38, '//li[@class=\'sect \']//a[not(@href=\'/katalog-oborudovaniya/Posuda-inventar/nozhi/\') and not(@href=\'/katalog-oborudovaniya/Posuda-inventar/Kukhonnaya-naplitnaya-posuda/\')]', 5, 1),
(39, '//div[@id=\'h1_and_compare\']//h1', 5, 2),
(40, '//div[@class=\'item-title\']//a/@href', 5, 3),
(41, '?PAGEN_1=', 5, 4),
(42, '//div[@id=\'h1_and_compare\']//h1', 5, 5),
(43, '//div[@class=\'detail_text\']', 5, 6),
(44, '//div[@class=\'preview_text\']', 5, 7),
(45, '//div[@class=\'slides\']//img/@src', 5, 8),
(46, '//table[@class=\'props_list\']', 5, 12),
(47, '//ul[@class=\'folders-shared\']//a', 8, 1),
(48, '//div[@class=\'content-inner\']//h1', 8, 2),
(49, '//div[@class=\'product-name\']//a//@href', 8, 3),
(50, '/p/', 8, 4),
(51, '//div[@class=\'content-inner\']//h1', 8, 5),
(52, '//div[@id=\'shop2-tabs-2\']', 8, 6),
(53, '//div[@class=\'product-image2\']//img/@src', 8, 8),
(54, '//div[@class=\'price-current\']//strong', 8, 10),
(55, '//table[@class=\'shop2-product-options\']', 8, 12),
(56, '//div[@class=\'right-data\']//div[@class=\'item-title\']//a', 6, 1),
(57, '//div[@id=\'h1_and_compare\']//h1', 6, 2),
(58, '//div[@class=\'item-title\']//a/@href', 6, 3),
(59, '?PAGEN_1=', 6, 4),
(60, '//div[@id=\'h1_and_compare\']//h1', 6, 5),
(61, '//div[@class=\'detail_text\']', 6, 6),
(62, '//div[@class=\'preview_text\']', 6, 7),
(63, '//div[@class=\'slides\']//img/@src', 6, 8),
(64, '//table[@class=\'props_list\']', 6, 12),
(65, '//div[@class=\'right-data\']//div[@class=\'item-title\']//a', 7, 1),
(66, '//div[@id=\'h1_and_compare\']//h1', 7, 2),
(67, '//div[@class=\'item-title\']//a/@href', 7, 3),
(68, '?PAGEN_1=', 7, 4),
(69, '//div[@id=\'h1_and_compare\']//h1', 7, 5),
(70, '//div[@class=\'detail_text\']', 7, 6),
(71, '//div[@class=\'preview_text\']', 7, 7),
(72, '//div[@class=\'slides\']//img/@src', 7, 8),
(73, '//table[@class=\'props_list\']', 7, 12),
(74, '/bitrix/templates/aspro_mshop/images/no_photo_medium.png', 3, 9),
(75, '//nav[@id=\'hmenu\']//li//a', 9, 1),
(77, '//div[@id=\'text\']//h1', 9, 2),
(78, '//div[@class=\'catitem\']//a/@href', 9, 3),
(79, '//div[@id=\'text\']//h1', 9, 5),
(80, '//p/big', 9, 6),
(81, '//a[@id=\'mim\']/@href', 9, 8),
(83, '//span[@itemprop=\'price\']', 9, 10),
(84, '/images/timthumb.php?src=&w=450&h=450&zc=2', 9, 9),
(85, '//div[@id=\'prodchars\']', 9, 12),
(86, '//td[@class=\'active\'][3]', 4, 10);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `curl`
--
ALTER TABLE `curl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_curl_sites1_idx` (`sites_id`);

--
-- Индексы таблицы `curl_auth`
--
ALTER TABLE `curl_auth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_curl_auth_sites1_idx` (`sites_id`);

--
-- Индексы таблицы `description`
--
ALTER TABLE `description`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_description_goods1_idx` (`goods_id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uri_goods` (`uri_goods`),
  ADD KEY `fk_goods_sites_idx` (`sites_id`),
  ADD KEY `fk_goods_groups_idx` (`groups_id`) USING BTREE;

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_images_goods1_idx` (`goods_id`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_logs_goods1_idx` (`goods_id`);

--
-- Индексы таблицы `logs_curl`
--
ALTER TABLE `logs_curl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_logs_curl_sites1_idx` (`sites_id`);

--
-- Индексы таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_manufacturer_goods1_idx` (`goods_id`);

--
-- Индексы таблицы `name_regular`
--
ALTER TABLE `name_regular`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_price_goods1_idx` (`goods_id`);

--
-- Индексы таблицы `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_attributes_goods1_idx` (`goods_id`);

--
-- Индексы таблицы `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `xpath`
--
ALTER TABLE `xpath`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_xpath_regular_name_regular1_idx` (`name_regular_id`),
  ADD KEY `fk_xpath_regular_sites1_idx` (`sites_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `curl`
--
ALTER TABLE `curl`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `curl_auth`
--
ALTER TABLE `curl_auth`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `description`
--
ALTER TABLE `description`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=626;
--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2710;
--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;
--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2195;
--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2710;
--
-- AUTO_INCREMENT для таблицы `logs_curl`
--
ALTER TABLE `logs_curl`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;
--
-- AUTO_INCREMENT для таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `name_regular`
--
ALTER TABLE `name_regular`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `price`
--
ALTER TABLE `price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;
--
-- AUTO_INCREMENT для таблицы `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `xpath`
--
ALTER TABLE `xpath`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `curl`
--
ALTER TABLE `curl`
  ADD CONSTRAINT `fk_curl_sites1` FOREIGN KEY (`sites_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `curl_auth`
--
ALTER TABLE `curl_auth`
  ADD CONSTRAINT `fk_curl_auth_sites1` FOREIGN KEY (`sites_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `description`
--
ALTER TABLE `description`
  ADD CONSTRAINT `fk_description_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `fk_goods_groups1` FOREIGN KEY (`groups_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_goods_sites` FOREIGN KEY (`sites_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `logs_curl`
--
ALTER TABLE `logs_curl`
  ADD CONSTRAINT `fk_logs_curl_sites1` FOREIGN KEY (`sites_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD CONSTRAINT `fk_manufacturer_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `fk_price_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `fk_product_attributes_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `xpath`
--
ALTER TABLE `xpath`
  ADD CONSTRAINT `fk_xpath_regular_name_regular1` FOREIGN KEY (`name_regular_id`) REFERENCES `name_regular` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_xpath_regular_sites1` FOREIGN KEY (`sites_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
