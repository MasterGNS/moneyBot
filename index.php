<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'Database.php';
require_once 'UserBalance.php';

use Telegram\Bot\Api;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
$userBalance = new UserBalance($db);

try {
    $bot = new \TelegramBot\Api\Client($_ENV['TELEGRAM_BOT_API']);
    
    // Получаем данные от Telegram
    $updates = json_decode(file_get_contents('php://input'), true);
    
    // Регистрация команды /start
    $bot->command('start', function ($message) use ($bot, $userBalance) {
        $chatId = $message->getChat()->getId();
        $newUser = $userBalance->createUser($chatId);
        if($newUser){
            $bot->sendMessage($chatId,"
            Приветствуем вас!\n\n" .
            "Этот бот поможет вам отслеживать ваш баланс. Вы можете добавлять или убавлять деньги, просто отправляя сумму в сообщении. " .
            "Например, отправьте `100` для добавления 100 единиц или `-50` для уменьшения баланса на 50 единиц.\n\n" .
            "Вы также можете использовать числа с плавающей точкой — например, `+25.50` или `-10,75`. Бот поддерживает как точку, так и запятую для разделения дробной части!\n\n" . 
            "Для просмотра баланса отправьте сообщение 'Баланс'"
        );
            $bot->sendMessage($chatId, 'Ваш текущий баланс: $0');
        } else{
            $bot->sendMessage($chatId, 'Произошла ошибка, попробуйте позже');
        }
    });

    $bot->on(function($update) use ($bot, $userBalance) {
        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();
        $text = $message->getText();
        if ($text === 'Баланс') {
            $balance = $userBalance->getUserBalance($chatId);
            
            if ($balance !== null) {
                $bot->sendMessage($chatId, "Ваш текущий баланс: $" . $balance);
            } else {
                $bot->sendMessage($chatId, "Не удалось получить ваш баланс. Попробуйте позже.");
            }
        }else {
            if (substr($text, 0, 1) !== '/') {
                str_replace(',','.',$text);
                $cleanedStr = preg_replace('/[^0-9.-]/', '', $text);
                if(preg_match('/^-?\d+(\.\d+)?$/', $cleanedStr) && $cleanedStr == $text){
                    $newBalance = $userBalance->updateUserBalance($chatId,$text);
                    if($newBalance !== null){
                        $bot->sendMessage($chatId, 'Ваш обновленный баланс: $' . $newBalance);
                    } else{
                        $bot->sendMessage($chatId, 'На вашем балансе недостаточно средств, для проведения операции');
                    }
                }else{
                    $bot->sendMessage($chatId, 'Неверно введенное число');
                }
            } else {
                $bot->sendMessage($chatId, 'Я не знаю комманд, кроме комманды /start');
            }
        }
    },  function ($update) {
        $message = $update->getMessage();
        return !is_null($message);
    });

    // Запуск обработки команд
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    error_log("Ошибка: " . $e->getMessage());
}
?>
