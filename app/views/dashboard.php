<table border="1">
    <tr>
        <th>ID</th>
        <th>Контейнер</th>
        <th>Клиент</th>
        <th>Менеджер</th>
        <th>Статус</th>
        <th>Дата прибытия</th>
        <?php if ($_SESSION['role'] !== 'client') {
            echo'<th>Редактирование</th>';
        }?>
    </tr>
    <?php foreach ($cargos as $cargo): ?>
        <tr>
            <td><?= $cargo['id'] ?></td>
            <td><?= $cargo['container'] ?></td>
            <td><a href="/client/details/<?= $cargo['client_id'] ?>" target="_blank"><?= $cargo['company_name'] ?></a></td>
            <td><a href="/manager/details/<?= $cargo['manager_id'] ?>" target="_blank"><?= $cargo['manager_name'] ?></a></td>
            <td><?= $cargo['status'] ?></td>
            <td><?= $cargo['arrival_date'] ?></td>
            <?php if ($_SESSION['role'] !== 'client') {
                echo '<td><a href="/cargo/edit/' . $cargo['id'] .'" target="_blank">Редактировать</a></td>';
            }?>
            
        </tr>
    <?php endforeach; ?>
</table>

<a href="/cargo/new" target="_blank">Завести груз</a>

<?php 
if ($_SESSION['role'] === 'manager') {
    echo '<a href="/cargo/assign-new-cargo" target="_blank">Новые грузы</a>';
}
?>

<a href="/cargo/export">Выгрузить в Excel</a>