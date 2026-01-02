#!/bin/bash
# Скрипт для полного workflow: commit → push → deploy

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo "=========================================="
echo "WordPress Reviews Plugin - Workflow"
echo "=========================================="

# Проверяем статус
echo "Checking git status..."
git status

# Спрашиваем, хотим ли продолжить
read -p "Продолжить? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Отменено."
    exit 0
fi

# Проверяем, есть ли изменения для коммита
if git diff-index --quiet HEAD --; then
    echo "Нет изменений для коммита."
    read -p "Все равно развернуть на сервер? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 0
    fi
    # Запускаем только деплой
    ./deploy.sh
    exit 0
fi

# Показываем изменения
echo ""
echo "Изменения:"
git diff --stat

# Спрашиваем сообщение коммита
echo ""
read -p "Введите сообщение коммита: " commit_message

if [ -z "$commit_message" ]; then
    echo "ERROR: Сообщение коммита не может быть пустым!"
    exit 1
fi

# Добавляем все изменения
echo "Adding changes..."
git add .

# Коммитим
echo "Committing changes..."
if ! git commit -m "$commit_message"; then
    echo "ERROR: Не удалось создать коммит!"
    exit 1
fi

# Запускаем деплой
echo ""
./deploy.sh

