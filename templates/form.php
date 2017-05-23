<form class="form form--add-lot container
      <?= !empty($invalid_controls) ? 'form--invalid' : '' ?>"
      action="/add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($invalid_controls['lot-name']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"
                   value="<?= $_POST['lot-name'] ?? '' ?>" required>
            <?= isset($invalid_controls['lot-name']) ?
                '<span class="form__error">'.$invalid_controls['lot-name'].'</span>' : '' ?>
        </div>
        <div class="form__item <?= isset($invalid_controls['category']) ? 'form__item--invalid' : '' ?>">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category) : ?>
                    <option <?= (isset($_POST['category']) &&
                        $_POST['category'] == $category['id']) ? 'selected' : ''?>
                        value=<?= $category['id'] ?>><?= $category['title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?= isset($invalid_controls['category']) ?
                '<span class="form__error">'.$invalid_controls['category'].'</span>' : '' ?>
        </div>
    </div>
    <div class="form__item form__item--wide
                    <?= isset($invalid_controls['message']) ? 'form__item--invalid' : ''; ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message"
                  placeholder="Напишите описание лота" required><?= $_POST['message'] ?? '' ?></textarea>
        <?= isset($invalid_controls['message']) ?
            '<span class="form__error">'.$invalid_controls['message'].'</span>' : '' ?>
    </div>
    <div class="form__item form__item--file">
        <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="photo2">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small
                <?= isset($invalid_controls['lot-rate']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="1000"
                   value="<?= $_POST['lot-rate'] ?? '' ?>" required>
            <?= isset($invalid_controls['lot-rate']) ?
                '<span class="form__error">'.$invalid_controls['lot-rate'].'</span>' : '' ?>
        </div>
        <div class="form__item form__item--small
                <?= isset($invalid_controls['lot-step']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="100"
                   value="<?= $_POST['lot-step'] ?? '' ?>" required>
            <?= isset($invalid_controls['lot-step']) ?
                '<span class="form__error">'.$invalid_controls['lot-step'].'</span>' : '' ?>
        </div>
        <div class="form__item <?= isset($invalid_controls['lot-date']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-date">Дата завершения</label>
            <input class="form__input-date" id="lot-date"
                   type="text" name="lot-date" placeholder="<?= date('d.m.Y', strtotime(' +1 week')); ?>"
                   value="<?= $_POST['lot-date'] ?? date('d.m.Y', strtotime(' +1 week')) ?>" required>
            <?= isset($invalid_controls['lot-date']) ?
                '<span class="form__error">'.$invalid_controls['lot-date'].'</span>' : '' ?>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>