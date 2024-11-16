# Anime API

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd anime-api

2. composer install

3. in .env file

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=anime_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

4. php artisan migrate

5. php artisan anime:fetch