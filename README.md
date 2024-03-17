# InspireLink

## Установка и запуск (Docker)

Для развертывания проекта выполните следующие шаги:

### Настройка окружения

1. **Создание файла .env**:
    - Скопируйте содержимое `.env.example` в новый файл `.env`. Все необходимые стандартные переменные окружения уже настроены.

### Сборка и запуск проекта

2. **Сборка контейнеров**:
    - Соберите проект командой: `docker-compose up --build -d`.

### Backend

3. **Создание таблиц базы данных**:
    - Выполните: `docker-compose exec backend php artisan migrate`.
4. **(Опционально) Заполнение базы данных фейковыми данными**:

    - Выполните: `docker-compose exec backend php artisan db:seed`.
    - Пароль для всех пользователей, созданных сидером, будет 'password'.

5. **Доступ к API**:
    - Используйте `localhost/api` или `localhost:8000/api` для доступа к API (коллекция путей для Postman находится в папке backend (PostmanCollection.json))

### Frontend

6. **Установка зависимостей**:
    - Выполните: `docker-compose exec frontend npm install`.
7. **Запуск frontend**:
    - Выполните: `docker-compose exec frontend npm run start`.

### Доступ к приложению

8. **Доступ к frontend**:
    - Откройте `localhost` в вашем браузере.

### Тестирование

9. **Запуск автотестов**:
    - Автотесты можно запустить командой: `docker-compose exec backend php artisan test`

## (Для разработчиков) Дополнительные шаги конфигурации
