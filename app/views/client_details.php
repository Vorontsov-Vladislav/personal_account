<h2>Детали клиента</h2>

<p>Email: <?= htmlspecialchars($client['email']) ?></p>
<p>Компания: <?= htmlspecialchars($client['company_name']) ?></p>
<p>ИНН: <?= htmlspecialchars($client['inn']) ?></p>
<p>Адрес: <?= htmlspecialchars($client['address']) ?></p>
<p>Телефон: <?= htmlspecialchars($client['phone']) ?></p>

<a href="/dashboard">Назад</a>