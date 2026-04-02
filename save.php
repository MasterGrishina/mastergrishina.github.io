<?php
// 1. Устанавливаем кодировку, чтобы русский текст не стал "кракозябрами"
header('Content-Type: text/html; charset=utf-8');

// 2. Проверяем, что данные пришли методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Получаем данные из полей (используем те же ключи, что в JS FormData)
    $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $date  = date('d.m.Y H:i:s'); // Время заявки

    // Если имя или телефон пустые — не сохраняем
    if (empty($name) || empty($phone)) {
        http_response_code(400);
        echo "Ошибка: Имя и телефон обязательны";
        exit;
    }

    // 3. Подготовка строки для CSV
    // Используем точку с запятой ";" как разделитель (удобно для Excel)
    $dataLine = [$date, $name, $phone, $email];

    // 4. Запись в файл
    // 'a' — режим дозаписи в конец файла. Если файла нет, PHP попробует его создать.
    $f = fopen('contacts.csv', 'a');
    
    if ($f) {
        // fputcsv автоматически обработает кавычки и спецсимволы
        fputcsv($f, $dataLine, ';');
        fclose($f);
        
        http_response_code(200);
        echo "Заявка успешно сохранена в CSV";
    } else {
        // Если файл не открылся (проблема с правами доступа на сервере)
        http_response_code(500);
        echo "Ошибка: Не удалось открыть файл для записи. Проверьте права папки (777/755).";
    }
} else {
    // Если кто-то просто зашел на save.php через браузер
    http_response_code(405);
    echo "Метод не разрешен";
}
?>
