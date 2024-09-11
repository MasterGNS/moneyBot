<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $bot = new \TelegramBot\Api\Client($_ENV['TELEGRAM_BOT_API']);
    $bot->setWebhook($_ENV['URL']);
    echo "Webhook установлен!";
} catch (\TelegramBot\Api\Exception $e) {
    echo "Ошибка установки вебхука: " . $e->getMessage();
}