
<form class="form container
      <?= !empty($invalid_controls) ? 'form--invalid' : '' ?>"
      action="/login.php" method="post" enctype="multipart/form-data">

    <?php if ($is_new_user) : ?>
        <p>Теперь вы можете войти, используя свой email и пароль.</p>
    <?php endif; ?>

    <h2>Вход</h2>
    <div class="form__item <?= isset($invalid_controls['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?= $_POST['email'] ?? '' ?>" required>
        <?= isset($invalid_controls['email']) ?
            '<span class="form__error">'.$invalid_controls['email'].'</span>' : '' ?>
    </div>
    <div class="form__item form__item--last <?= isset($invalid_controls['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" required>
        <?= isset($invalid_controls['password']) ?
            '<span class="form__error">'.$invalid_controls['password'].'</span>' : '' ?>
    </div>
    <button type="submit" class="button">Войти</button>
</form>