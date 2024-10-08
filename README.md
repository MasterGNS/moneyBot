# Telegram Бот для Управления Балансом

Этот проект представляет собой Telegram-бота, который позволяет пользователям отслеживать и изменять их баланс. Бот поддерживает простые команды для взаимодействия с балансом и использования чисел с плавающей точкой для пополнения или уменьшения баланса.

## Возможности

- Команда `/start` регистрирует пользователя в базе данных с нулевым балансом.
- Просмотр баланса с помощью текстовой команды "Баланс".
- Изменение баланса путем отправки положительных или отрицательных чисел (например, `100`, `-50`, `25.50`).
- Бот поддерживает как точку, так и запятую для дробной части числа.
- Валидация введенных данных: бот принимает только корректные числовые значения.

## Установка

1. Клонируйте репозиторий:

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
```
Вот пример файла `README.md` для вашего проекта Telegram-бота:

```markdown
# Telegram Бот для Управления Балансом

Этот проект представляет собой Telegram-бота, который позволяет пользователям отслеживать и изменять их баланс. Бот поддерживает простые команды для взаимодействия с балансом и использования чисел с плавающей точкой для пополнения или уменьшения баланса.

## Возможности

- Команда `/start` регистрирует пользователя в базе данных с нулевым балансом.
- Просмотр баланса с помощью текстовой команды "Баланс".
- Изменение баланса путем отправки положительных или отрицательных чисел (например, `100`, `-50`, `25.50`).
- Бот поддерживает как точку, так и запятую для дробной части числа.
- Валидация введенных данных: бот принимает только корректные числовые значения.

## Установка

1. Клонируйте репозиторий:

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
```

2. Установите зависимости через Composer:

```bash
composer install
```

3. Создайте файл `.env` и настройте его с вашими параметрами базы данных и API Telegram:

```bash
DB_HOST=your_host
DB_PORT=your_port
DB_USERNAME=your_username
DB_PASSWORD=your_password
DB_NAME=your_database_name
TELEGRAM_BOT_API=your_telegram_bot_api_key
URL=your_webhook_url
```

4. Настройте базу данных, добавив таблицу `users` с полями `id` (INTEGER) и `balance` (FLOAT):

```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY,
    balance FLOAT NOT NULL DEFAULT 0
);
```

## Использование

- Команда `/start` регистрирует пользователя и возвращает сообщение с текущим балансом.
- Текстовая команда "Баланс" показывает текущий баланс пользователя.
- Чтобы изменить баланс, отправьте число (например, `100` для добавления или `-50` для уменьшения). Для дробных значений можно использовать как точку (`.`), так и запятую (`,`).

Пример использования:
- `+100` добавляет 100 единиц к балансу.
- `-50.75` уменьшает баланс на 50.75 единиц.
- Отправка "Баланс" покажет текущий баланс пользователя.

## Запуск проекта

1. Настройте webhook для вашего бота, используя URL вашего сервера.
2. Запустите сервер на вашем локальном компьютере или хостинге.

Для локального запуска используйте [ngrok](https://ngrok.com/) для создания публичного URL для вашего webhook:

```bash
ngrok http 80
```

## Примечания

- В проекте используется библиотека [Telegram Bot API](https://github.com/telegram-bot/api) для работы с Telegram.
