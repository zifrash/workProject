## Make command:
Команда первого запуска, запустит наши образы (php, nginx, postgres, redis, rabbitmq), и произведет первичную настройку. Так же запустит composer install.
```bash
make first-build
```
Удалит папку vendor (composer), а так же все докер образы. И заново все соберет (first-build).
```bash
make hard-rebuild
```
Скачает, докер образы и запустит их.
```bash
make build
```
Удалит докер образы.
```bash
make destroy
```
Запустит работу докер образов.
```bash
make start
```
Остановит работу докер образов.
```bash
make stop
```
Перезапустит докер образы.
```bash
make restart
```
Переустановит докер образы (destroy build).
```bash
make rebuild
```
Установит пакеты composer (composer.json).
```bash
make composer-install
```
Удалит пакеты composer (папку vendor).
```bash
make composer-remove
```

## ENV файл:
В .env файле лежат найстройки для подключения к postgres, redis, rabbitmq. На продакшене, этот файл добавляется в .gitignore.

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

## Полезная информация:
[Установить docker на убунту](https://docs.docker.com/engine/install/ubuntu/#set-up-the-repository)\
[Установить плагин compose на docker](https://docs.docker.com/compose/install/linux/#install-the-plugin-manually)