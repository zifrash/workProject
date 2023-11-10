## Make команды:
Рекомендую использовать make команды, что бы увидеть список команд и их описание, выполните в консоли:
```bash
make
```
Например, одна из команд:
```bash
make first-build
```
Команда первого запуска, запустит наши образы (php, nginx, postgres, redis, rabbitmq), и произведет первичную настройку. Так же запустит composer install.

## ENV файл:
В .env файле лежат найстройки для подключения к postgres, redis, rabbitmq. Так же настройка часового пояса. На продакшене, этот файл добавляется в .gitignore.

## Папка docker
В папке docker лежат файлы найстроек nginx (docker/conf.d/default.conf) и php (docker/custom.php.ini)

## Полезные команды:
Пересоздает файлы с зависимостями композера.
```bash
docker compose exec php composer dump-autoload
```
Проверяет что с композером все ок.
```bash
docker compose exec php composer diagnose
```
Проверяет что с файлом composer.json все ок.
```bash
docker compose exec php composer validate
```

Обновить composer под root пользователем
```bash
docker compose exec -u root php composer self-update
```

## Полезная информация:
[Установить docker на убунту](https://docs.docker.com/engine/install/ubuntu/#set-up-the-repository)\
[Установить плагин compose на docker](https://docs.docker.com/compose/install/linux/#install-the-plugin-manually)