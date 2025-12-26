#!/bin/bash
# Скрипт для обновления плагина на сервере (запускать на сервере)

PLUGIN_PATH="/var/www/reviews-site/wp-content/plugins/wordpress-reviews-plugin"

cd $PLUGIN_PATH

# Проверить, есть ли изменения для получения
git fetch origin

# Получить изменения
git pull origin main

# Установить правильные права
chown -R www-data:www-data $PLUGIN_PATH
find $PLUGIN_PATH -type f -exec chmod 644 {} \;
find $PLUGIN_PATH -type d -exec chmod 755 {} \;

echo "Plugin updated successfully!"

