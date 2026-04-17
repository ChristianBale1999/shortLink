Сервис для создания коротких ссылок и QR-кодов на Yii2 с Docker.

- Docker
- Docker Compose

# 1. Клонируйте репозиторий
git clone https://github.com/ChristianBale1999/shortLink.git
cd shortlink-service

# 2. Оставил для теста
cp .env

# 3. Запустите контейнеры
docker-compose up -d --build

# 4. Запустите миграцию
docker exec -it shortlink_php php yii migrate/up --interactive=0

# 6. Откройте в браузере
# http://localhost