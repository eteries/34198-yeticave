INSERT INTO categories (title)
VALUES
  ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Инструменты'),
  ('Одежда'),
  ('Разное');

INSERT INTO users (reg_time, username, email, password, avatar, contact_info)
VALUES
  ('2017-04-19 12:23:55',
   'Игнат',
   'ignat.v@gmail.com',
   '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka',
   'img/avatar.jpg',
   NULL),

  ('2017-03-9 05:23:55',
   'Леночка',
   'kitty_93@li.ru' ,
   '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa',
   'img/rate5.jpg',
   'Телефон +7 900 667-84-48,Скайп: Vlas92. Звонить с 14 до 20'),

  ('2017-05-14 15:23:12',
   'Руслан',
   'warrior07@mail.ru',
   '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW',
   'img/user.jpg',
   NULL);

INSERT INTO lots (title, lot_category, picture, starting_price, bid_step, creation_date, ending_date, author_id)
VALUES
  ('2014 Rossignol District Snowboard', 1, 'img/lot-1.jpg', '10999', NULL, NOW(), '2017-05-14 5:23:12', 1),
  ('DC Ply Mens 2016/2017 Snowboard', 1, 'img/lot-2.jpg', '159999', 1000, NOW(), '2017-05-20 17:23:43', 1),
  ('Крепления Union Contact 2015 года размер L/XL', 3, 'img/lot-3.jpg', '8000', 10, NOW(), '2017-05-9 2:23:55', 1),
  ('Ботинки для сноуборда DC Mutiny Charocal', 3, 'img/lot-4.jpg', '10999', 300, NOW(), '2017-05-30 11:15:21', 3),
  ('Куртка для сноуборда DC Mutiny Charocal', 4, 'img/lot-5.jpg', '7500', 500, NOW(), '2017-05-11 18:53:14', 3),
  ('Маска Oakley Canopy', 5, 'img/lot-6.jpg', '5400', 1000, NOW(), '2017-05-25 10:13:40', 1);

INSERT INTO bids (placement_date, bid_amount, bid_author, bid_lot)
VALUES
  ('2017-05-14 10:23:12', 11000, 3, 1),
  ('2017-05-14 11:44:52', 11500, 2, 1),
  ('2017-05-15 16:05:27', 11600, 3, 1),
  ('2017-05-16 23:09:32', 12500, 2, 1);

