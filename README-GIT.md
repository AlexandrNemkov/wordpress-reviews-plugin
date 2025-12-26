# Git Setup для WordPress Reviews Plugin

## Текущий статус

✅ Локальный git репозиторий инициализирован
✅ Первый коммит создан
✅ Git настроен на сервере

## Следующие шаги

### 1. Создать удаленный репозиторий

Создайте репозиторий на GitHub, GitLab или другом сервисе:

**GitHub:**
1. Перейдите на https://github.com/new
2. Создайте новый репозиторий (например, `wordpress-reviews-plugin`)
3. НЕ инициализируйте его с README, .gitignore или лицензией

**GitLab:**
1. Перейдите на https://gitlab.com/projects/new
2. Создайте новый проект

### 2. Добавить remote и загрузить код

После создания репозитория выполните:

```bash
cd "/Users/aleksandrnemkov/Desktop/Скрипты для Руденко/wordpress-reviews-plugin"

# Добавить remote (замените URL на ваш)
git remote add origin https://github.com/ваш-username/wordpress-reviews-plugin.git
# или
git remote add origin git@github.com:ваш-username/wordpress-reviews-plugin.git

# Загрузить код
git push -u origin main
```

### 3. Настроить сервер для работы с git

На сервере уже настроен git. Для подключения к удаленному репозиторию:

```bash
ssh root@94.241.141.253

cd /var/www/reviews-site/wp-content/plugins/wordpress-reviews-plugin

# Добавить remote (замените URL на ваш)
git remote add origin https://github.com/ваш-username/wordpress-reviews-plugin.git
# или для SSH
git remote add origin git@github.com:ваш-username/wordpress-reviews-plugin.git

# Получить изменения
git pull origin main
```

### 4. Автоматическое обновление на сервере

Для автоматического обновления при push можно использовать:

**Вариант 1: Webhook (рекомендуется)**
- Настройте webhook в GitHub/GitLab, который будет вызывать скрипт на сервере
- Создайте скрипт на сервере: `/var/www/reviews-site/wp-content/plugins/wordpress-reviews-plugin/update.sh`

**Вариант 2: Использовать deploy.sh**
```bash
./deploy.sh
```

### 5. Работа с репозиторием

**Добавить изменения:**
```bash
git add .
git commit -m "Описание изменений"
git push origin main
```

**Обновить на сервере:**
```bash
ssh root@94.241.141.253 "cd /var/www/reviews-site/wp-content/plugins/wordpress-reviews-plugin && git pull origin main"
```

## Структура репозитория

```
wordpress-reviews-plugin/
├── .gitignore
├── README.md
├── README-GIT.md
├── deploy.sh
├── reviews-plugin.php
├── includes/
├── templates/
├── assets/
└── ...
```

## Важные файлы

- `.gitignore` - исключает временные файлы и архивы
- `deploy.sh` - скрипт для развертывания на сервере
- `README-GIT.md` - эта инструкция

