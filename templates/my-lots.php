<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if (empty($this_user_lots)) : ?>
        <p>Вы пока не делали ставок на существующие лоты.</p>
    <?php else : ?>
        <table class="rates__list">
            <?php foreach ($this_user_lots as $lot) : ?>
                <tr class="rates__item">
                    <td class="rates__info">
                        <div class="rates__img">
                          <img src="<?= $lot['picture'] ?>" width="54" height="40" alt="<?= $lot['title'] ?>">
                        </div>
                        <h3 class="rates__title">
                            <a href="/lot.php?id=<?= $lot['id'] ?>">
                                <?= $lot['title'] ?>
                            </a>
                        </h3>
                    </td>
                    <td class="rates__category">
                        <?= $lot['category'] ?>
                    </td>
                    <td class="rates__price">
                        <?= $lot['price'] ?> р
                    </td>
                    <td class="rates__time">
                        <?= $lot['placement_date']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>