<header class="main-header">
    <div class="main-header__container container">
        <h1 class="visually-hidden">YetiCave</h1>
        <a class="main-header__logo">
            <img src="img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
        </a>
        <form class="main-header__search" method="get" action="https://echo.htmlacademy.ru">
            <input type="search" name="search" placeholder="Поиск лота">
            <input class="main-header__search-btn" type="submit" name="find" value="Найти">
        </form>
        <a class="main-header__add-lot button" href="/add.php">Добавить лот</a>
        <?php if (isset($_SESSION['user'])) : ?>

            <nav class="user-menu">
                <div class="user-menu__image">
                    <img src="<?= isset($_SESSION['user']['avatar'])
                        ? strip_tags($_SESSION['user']['avatar'])
                        : 'img/user.jpg' ?>"
                         width="40" height="40" alt="<?= htmlspecialchars($_SESSION['user']['username']) ?>">
                </div>
                <div class="user-menu__logged">
                    <p><?= htmlspecialchars($_SESSION['user']['username']) ?></p>
                    <a href="/logout.php">Выйти</a>
                </div>
            </nav>

        <?php else : ?>

            <ul class="user-menu__list">
                <li class="user-menu__item">
                  <a href="/signup.php">Регистрация</a>
                </li>
                <li class="user-menu__item">
                  <a href="/login.php">Вход</a>
                </li>
            </ul>

        <?php endif; ?>
    </div>
</header>

<main>