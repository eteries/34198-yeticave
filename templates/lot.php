<section class="lot-item container">
    <h2><?= $lot['title'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['picture'] ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category'] ?></span></p>
            <p class="lot-item__description"><?= $lot['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($_SESSION['user'])) : ?>
                <?php if ($lot['active'] == true) : ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?= $lot['remaining_time'] ?>
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
                        <form class="lot-item__form <?= isset($error) ? 'form--invalid' : '' ?>"
                              action="" method="post">
                          <p class="lot-item__form-item <?= isset($error) ? 'form__item--invalid' : '' ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="number" name="cost" placeholder="<?= $lot['min'] ?>">
                          </p>
                          <button type="submit" class="button">Сделать ставку</button>
                            <?= !empty($error) ? '<div class="form__error">'.$error.'</div>' : '' ?>
                        </form>
                        <?php if (!empty($this_user_bids[0])) : ?>
                          <p>Ваша ставка: <?= $this_user_bids[0]['user_max'] ?></p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?= $lot['remaining_time'] ?>
                        </div>
                        <?php if (!empty($this_user_bids[0])) : ?>
                          <p>Ваша ставка: <?= $this_user_bids[0]['user_max'] ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (!empty($this_lot_bids)) : ?>
              <div class="history">
                <h3>История ставок (<span><?= count($this_lot_bids); ?></span>)</h3>
                    <?php foreach ($this_lot_bids as $bid) : ?>
                    <table class="history__list">
                      <tr class="history__item">
                        <td class="history__name"><?= $bid['username'] ?></td>
                        <td class="history__price"><?= $bid['bid_amount'] ?> р</td>
                        <td class="history__time"><?= $bid['placement_date'] ?></td>
                      </tr>
                    </table>
                    <?php endforeach; ?>
              </div>
                <?php else : ?>
            <div class="history">
              <h3>История ставок</h3>
              <p>Ставок для этого лота пока не было</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>