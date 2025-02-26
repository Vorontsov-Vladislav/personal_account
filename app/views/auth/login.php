<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
</head>
<body>
    <h2>Авторизация</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form action="/auth/authenticate" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Пароль:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Войти</button>
    </form>
</body>
</html>