<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Экспорт данных</title>
</head>
<body>
    <h2>Выберите способ экспорта</h2>
    <form action="/cargo/export/process" method="POST">
        <label>
            <input type="radio" name="export_type" value="download" checked>
            Скачать файл
        </label>
        <br>
        <label>
            <input type="radio" name="export_type" value="email">
            Отправить на email
        </label>
        <br><br>
        <button type="submit">Продолжить</button>
    </form>
</body>
</html>