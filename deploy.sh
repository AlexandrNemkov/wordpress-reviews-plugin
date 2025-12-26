#!/bin/bash
# Скрипт для развертывания плагина на сервере через git

SERVER="root@94.241.141.253"
PLUGIN_PATH="/var/www/reviews-site/wp-content/plugins/wordpress-reviews-plugin"

echo "Deploying to server..."

# Push changes to remote (if remote is configured)
if git remote -v | grep -q origin; then
    echo "Pushing to remote repository..."
    git push origin main
fi

# Pull changes on server
echo "Pulling changes on server..."
ssh $SERVER "cd $PLUGIN_PATH && git pull origin main"

echo "Deployment complete!"

