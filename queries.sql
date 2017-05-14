/* получить список из всех категорий */
SELECT * FROM categories;

/* получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, количество ставок, название категории */
SELECT lots.title, lots.starting_price, lots.picture, count(bids.id), max(bids.bid_amount), categories.title
FROM lots JOIN bids ON lots.id = bids.bid_lot
          JOIN categories ON lots.lot_category = categories.id
WHERE winner_id IS NULL
ORDER BY lots.creation_date DESC;

/* найти лот по его названию или описанию */
SELECT * FROM lots
WHERE title LIKE '%брюки%' OR description LIKE '%брюки%';

/* добавить новый лот (все данные из формы добавления) */
INSERT INTO lots
SET title = 'Брюки для сноубординга Craft',
    lot_category = 5,
    picture = 'img/1494759997.jpg',
    starting_price = 2700,
    bid_step = 300,
    creation_date = NOW(),
    ending_date = 1495367432,
    author_id = 1;

/* обновить название лота по его идентификатору */
UPDATE lots
SET title = 'Брюки для сноубординга Craft XL'
WHERE id = 4;

/* добавить новую ставку для лота */
INSERT INTO bids
SET placement_date = NOW(),
    bid_amount = 3200,
    bid_author = 1,
    bid_lot = 4;

/* получить список ставок для лота по его идентификатору */
SELECT bids.bid_amount FROM bids
JOIN lots ON bids.bid_lot = lots.id
WHERE lots.id = 4;
