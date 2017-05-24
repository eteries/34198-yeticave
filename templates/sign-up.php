<form class="form container
      <?= !empty($invalid_controls) ? 'form--invalid' : '' ?>"
      action="/signup.php" method="post" enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($invalid_controls['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $_POST['email'] ?? '' ?>" required>
        <?= isset($invalid_controls['email']) ?
            '<span class="form__error">'.$invalid_controls['email'].'</span>' : '' ?>
    </div>
    <div class="form__item <?= isset($invalid_controls['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль" value="<?= $_POST['password'] ?? '' ?>" required>
        <?= isset($invalid_controls['password']) ?
            '<span class="form__error">'.$invalid_controls['password'].'</span>' : '' ?>
    </div>
    <div class="form__item <?= isset($invalid_controls['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= $_POST['name'] ?? '' ?>" required>
        <?= isset($invalid_controls['name']) ?
            '<span class="form__error">'.$invalid_controls['name'].'</span>' : '' ?>
    </div>
    <div class="form__item <?= isset($invalid_controls['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"
                  required><?= $_POST['message'] ?? ''; ?></textarea>
        <?= isset($invalid_controls['message']) ?
            '<span class="form__error">'.$invalid_controls['message'].'</span>' : '' ?>
    </div>
    <div class="form__item form__item--file form__item--last">
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
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>