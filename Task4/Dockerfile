# Используем официальный образ PHP с Apache в качестве базового образа
FROM php:8.2-apache

# Обновляем список пакетов и устанавливаем расширения для работы с PDO и MySQL
RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Включаем модуль mod_rewrite для Apache
RUN a2enmod rewrite

# Устанавливаем ServerName в конфигурации Apache, чтобы подавить предупреждение о FQDN
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Меняем DocumentRoot на папку "public", чтобы Apache искал index.php и .htaccess именно там
# Эта команда заменяет все вхождения "/var/www/html" на "/var/www/html/public" в файле конфигурации виртуального хоста
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Копируем весь проект в контейнер по пути /var/www/html
# Так, если в корневой папке проекта есть папка public, то после замены DocumentRoot будет /var/www/html/public
COPY . /var/www/html

# Открываем порт 80 для Apache
EXPOSE 80

# Указываем команду для запуска Apache в переднем плане
CMD ["apache2-foreground"]
