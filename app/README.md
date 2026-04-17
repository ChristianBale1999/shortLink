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

# 4. Установите зависимости Composer
docker exec -it shortlink_php composer install

# 5. Создайте таблицы в базе данных
docker exec -it shortlink_mysql mysql -uroot -prootpassword -e "
CREATE DATABASE IF NOT EXISTS shortlink_db;
USE shortlink_db;
CREATE TABLE IF NOT EXISTS links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    original_url VARCHAR(2048) NOT NULL,
    short_code VARCHAR(10) NOT NULL UNIQUE,
    clicks INT DEFAULT 0,
    created_at INT NOT NULL,
    updated_at INT NOT NULL
);
CREATE TABLE IF NOT EXISTS clicks_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255),
    clicked_at INT NOT NULL,
    FOREIGN KEY (link_id) REFERENCES links(id) ON DELETE CASCADE
);
"

# 6. Откройте в браузере
# http://localhost