<h2>Детали менеджера</h2>
<p>Имя: <?= htmlspecialchars($manager['first_name'] . ' ' . $manager['last_name']) ?></p>
<p>Email: <?= htmlspecialchars($manager['email']) ?></p>
<p>Телефон: <?= htmlspecialchars($manager['phone']) ?></p>

<a href="/dashboard">Назад</a>