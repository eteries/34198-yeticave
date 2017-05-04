<section class="lot-item container">
    <h2><?= $lot['title'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['img'] ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category'] ?></span></p>
            <p class="lot-item__description"><?= $lot['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                  <?= $lot_time_remaining ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= $lot['price'] ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= $lot['min'] ?> р</span>
                    </div>
                </div>
                <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
                    <p class="lot-item__form-item">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="number" name="cost" placeholder="<?= $lot['min'] ?>">
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <div class="history">
                <h3>История ставок (<span>4</span>)</h3>
                <?php foreach ($bets as $bet) : ?>
                    <table class="history__list">
                        <tr class="history__item">
                            <td class="history__name"><?= $bet['name'] ?></td>
                            <td class="history__price"><?= $bet['price'] ?> р</td>
                            <td class="history__time"><?= formatElapsedTime($bet['ts']) ?></td>
                        </tr>
                    </table>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>