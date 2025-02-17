<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PHP-labs</title>
</head>
<body>
    <div class="form-container">
        <h1>Добавление тренера</h1>
        <form action="form.php" method="POST">
            <div class="form-line">
                <label for="name">Тренер:</label>
                <input type="text" id="name" name="name" required placeholder="Введите имя">
            </div>
            <div class="form-line">
                <label for="age">Возраст:</label>
                <input type="text" id="age" name="age" required placeholder="Введите возраст">
            </div>
            <div class="form-line">
                <label for="gender">Пол:</label>
                <select id="gender" name="gender" required>
                    <option value="male">Мужской</option>
                    <option value="female">Женский</option>
                </select>
            </div>
            <div class="form-line">
                <label for="phone">Телефон:</label>
                <input type="tel" id="phone" name="phone" required placeholder="+79991234567">
            </div>
            <div class="form-line">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="your@email.com">
            </div>
            <input type="submit" value="Отправить">
        </form>
    </div>
</body>
</html>
