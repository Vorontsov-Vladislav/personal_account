
<?php 

if (empty($cargos)) {
    echo 'Новых грузов нет<br>';
    echo '<a href="/dashboard">Назад</a>';
    die;
}

?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Контейнер</th>
        <th>Клиент</th>
        <th>Статус</th>
        <th>Действие</th>
    </tr>
    <?php foreach ($cargos as $cargo): ?>
        <tr>
            <td><?= $cargo['id'] ?></td>
            <td><?= $cargo['container'] ?></td>
            <td><a href="/client/details/<?= $cargo['client_id'] ?>" target="_blank"><?= $cargo['company_name'] ?></a></td>
            <td><?= $cargo['status'] ?></td>
            <?= 
                '<td><form action="/cargo/assign" method="POST">
                <input type="hidden" name="cargo_id" value=' . $cargo['id'] . '>
                <button type="submit">Назначить себя</button>
                </form></td>';
            ?>
        </tr>
    <?php endforeach; ?>
</table>

<a href="/dashboard">Назад</a>