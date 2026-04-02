<?php
// --- НАСТРОЙКИ БОТА ---
$botToken = "ВАШ_API_КЛЮЧ_БОТА"; 
$chatId = "ВАШ_CHAT_ID"; 
// -----------------------

$name = $_POST['name'] ?? 'Не указано';
$phone = $_POST['phone'] ?? 'Не указано';
$email = $_POST['email'] ?? 'Не указано';
$date = date('d.m.Y H:i');

// 1. СОХРАНЕНИЕ В CSV
$filename = 'contacts.csv';
$file = fopen($filename, 'a');

// Если файл пустой, добавим заголовки
if (filesize($filename) == 0) {
    fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM для Excel
    fputcsv($file, array('Дата', 'Имя', 'Телефон', 'Email'), ';');
}

fputcsv($file, array($date, $name, $phone, $email), ';');
fclose($file);

// 2. ОТПРАВКА В TELEGRAM
$text = "🔔 Новая заявка с сайта!\n\n";
$text .= "📅 Дата: $date\n";
$text .= "👤 Имя: $name\n";
$text .= "📞 Тел: $phone\n";
$text .= "✉️ Email: $email\n\n";
$text .= "Добрый день, хочу записаться на курс обучения, подскажите пожалуйста подробности и ближайшую дату начала обучения, спасибо!";

$url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text=" . urlencode($text);

// Выполняем запрос к Telegram
file_get_contents($url);

echo "success";
?>
