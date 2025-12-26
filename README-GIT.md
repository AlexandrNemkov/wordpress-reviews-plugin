# Git Setup для WordPress Reviews Plugin

## Текущий статус

✅ Локальный git репозиторий инициализирован
✅ Первый коммит создан
✅ Git настроен на сервере
✅ **Удаленный репозиторий создан на GitHub**
✅ **Сервер синхронизирован с GitHub**

## Репозиторий

**GitHub:** https://github.com/AlexandrNemkov/wordpress-reviews-plugin

Репозиторий уже настроен и синхронизирован. Все готово к работе!

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

