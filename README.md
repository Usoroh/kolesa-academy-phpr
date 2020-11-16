# Сервис "Ресайзер" на PHP

Создать сервис обрезки и изменения размеров изображения на основе полученных параметров.

## Минимальный необходимый функционал:

Сервис должен реализовывать обработку подобных запросов:

`/?url=https%3A%2F%2Fjob.kolesa.kz%2Ffiles%2F000%2F000%2F_XUou011.jpg&size=1024x1024&cropping=0`

Где:

`url` - обязательный параметр, ссылка на изображение в формате jpg (jpeg) с разрешением сторон до 2048 на 2048 включительно.

`size` - обязательный параметр, размер изображения которое мы хотим получить на выходе в формате “ВЫСОТАхШИРИНА”. Минимальный размер 256 на 256, максимальный 1024 на 1024.

`cropping` - опциональный параметр, условия обрезки изображения, имеет два значения (0 или 1), по умолчанию 0.
_При параметре 0_ изображение не обрезается, большая сторона исходного изображения приводиться к размерам указанным в size, меньшая сторона меняется пропорционально. Изображение центрируется, а область не занятая изображением закрашивается белым.
_При параметре 1_ изображение обрезается, меньшая сторона исходного изображения приводится к размерам указанным в size, большая сторона изменяет свой размер пропорционально меньшей, а то что не помещается в новые размеры обрезается.

---

В случае успеха выполнения команды сервис должен вернуть новое изображение в формате jpg измененное согласно переданным параметрам.

В случае неверно заданных параметров необходимо вернуть ответ с кодом 400 в формате json, где будет единственное поле “error” содержащее текст ошибки в формате “ПАРАМЕТР - ТЕКСТ ОШИБКИ”.

Например если был пропущен параметр url в запросе:

`{
    “error”: “url - не указан обязательный параметр”
}`

Параметры валидируются в порядке url, size, croping. И возвращается самая первая ошибка.

---

## Дополнительное задание:

Если Вы успешно реализовали задание, и “отполировали” минимальный функционал можете приступить к внедрению дополнительных функций по выбору.

1) Опциональный параметр background. Работает только когда croping=0 Указывает закраску пустой области в RGB формате. Например background=0,0,0 должен будет закрасить область без изображения в черный цвет.
2) Параметр настройки качества сжатия изображения.
3) Работа с разными форматами изображения.
4) Кэширование результатов выполнения запросов.
5) Вести статистику запросов и хранить её в БД.

## Запуск тестов

Для запуска тестов нужно выполнить команду из корня проекта:

```
docker-compose up tests
```

Учтите что тесты покрывают не все кейсы, и если они проходят не факт что у Вас все реализовано правильно. 

## Полезные ссылки

[Обработка изображений (ImageMagick)](https://www.php.net/manual/ru/book.imagick.php)

