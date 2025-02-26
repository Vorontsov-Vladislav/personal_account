<form action="/cargo/update" method="POST">
    <?php var_dump($cargo['arrival_date'])?>
    <input type="hidden" name="id" value="<?=$cargo['id']?>">

    <label for="status">Статус</label>
    <select name="status" id="status">
        <option value="On board" <?= $cargo['status'] === 'On board' ? 'selected' : ''?>>On board</option>
        <option value="Finished" <?= $cargo['status'] === 'Finished' ? 'selected' : ''?>>Finished</option>
    </select>

    <label for="arrival_date">Дата прибытия:</label>
    <input type="date" name="arrival_date" id="arrival_date" value="<?=$cargo['arrival_date'] ? date('Y-m-d', strtotime($cargo['arrival_date'])) : ''?>">

    <button type="submit">Сохранить изменения</button>
</form>