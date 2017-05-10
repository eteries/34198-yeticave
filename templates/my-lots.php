<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if (empty($my_lots)) : ?>
        <p>Вы пока не делали ставок.</p>
    <?php else : ?>
        <table class="rates__list">
            <?php foreach ($my_lots as $my_lot) : ?>
                <tr class="rates__item">
                    <td class="rates__info">
                        <div class="rates__img">
                          <img src="<?= $my_lot['img'] ?>" width="54" height="40" alt="<?= $my_lot['title'] ?>">
                        </div>
                        <h3 class="rates__title"><a href="/lot.php?id=<?= $my_lot['id'] ?>">
                            2014 Rossignol District Snowboard</a>
                        </h3>
                    </td>
                    <td class="rates__category">
                        <?= $my_lot['category'] ?>
                    </td>
                    <td class="rates__price">
                        <?= $my_lot['cost'] ?> р
                    </td>
                    <td class="rates__time">
                        <?= formatElapsedTime($my_lot['posted']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>