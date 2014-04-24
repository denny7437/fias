FIAS
================

Осуществляет загрузку и обновление базы адресов из ФИАС.

Зависимости, помимо указанных в composer:
1. rar
2. unrar


Для инициализации необходимо запустить init.php. Поддерживаются 3 режима работы:

1. ```php init.php``` — запросит с сайта ФИАСа последнюю версию базы, скачает ее, распакует и импортирует.

2. ```php init.php /path/to/archive.rar``` — распакует и импортирует архив.

3. ```php init.php /path/to/fias_directory``` — импортирует уже распакованный архив.

Работа с API:
1. Дополнение адреса.
Запрос:
```fias.loc/api/complete/?pattern=Москва, Невск&limit=20``` — автодополнение с лимитом в двадцать записей.
Максимальное количество записей задается в конфигурационном файле, параметр ```app.max_completion_limit```, по умолчанию 50.
Если лимит не указан, выдается максимальное количество.
Также можно указать параметры:
```max_depth``` -- максимальная глубина, до которой будут искаться соответствия в адресной базе ФИАС.
```address_levels``` -- массив с уровнями записей (по ФИАС), которые нужно найти.
Доступные наименования уровней:
*   region -- регион: Санкт-Петербург, Московская область, Хабаровский край и т.д.
*   area -- округ: пока данные отсутствуют, заложено для дальнейшей совместимости с ФИАС, когда ФИАС перенесет часть элементов из region
*   area_district -- район округа/региона: Волжский район, Ломоносовский район, Гатчинский район и т.д.
*   city -- город: Петергоф, Сосновый бор, Пушкин...
*   city_district -- район города: микрорайон № 13, Кировский район, Центральный район и т.д.
*   settlement -- населенный пункт: поселок Парголово, станция Разлив, поселок Металлострой и т.д.
*   street -- улица: проспект Косыгина, улица Ярославская, проспект Художников
*   territory дополнительная территория: Рябинушка снт (садовое некоммерческое товарищество), Победа гск (гаражно-строительный кооператив)
*   sub_territory -- часть дополнительной территории: Садовая улица, 7-я линия и т.д.

```regions``` -- массив. Содержит номера регионов, по которым будет осуществлятся автодополнение. Для получения
номеров регионов обратитесь к файлу docs/regions.md

Ответ:
```
{
    "items": [
        {"is_complete": false, "title": "Москва, Невский пр.", "type": "address"},
        {"is_complete": false, "title": "Москва, Невское урочище", "type": "address"},
        {"is_complete": false, "title": "Невский вокзал", "type": "place"}
    ]
}
```

2. Валидация элемента.
Запрос:
```fias.loc/api/validate/?pattern=Москва, Невский пр.```
Ответ:
```
{
    "is_valid": true,
    "is_complete": false,
    "item_type": "address"
}
```
Параметр ```is_complete``` равен true если адрес полный (вместе с домом) и false в любом другом случае.
Параметр ```item_type``` может принимать три значения: null, если ничего не найдено, ```address``` если текст найден в ФИАС, ```place```, если текст- найден в списке places (аэропорты, вокзалы, порты и т.д.).

3. Получение адреса по индексу.
Запрос:
```fias.loc/api/mapping/?postal_code=198504```
Ответ:
```
{ "address_parts" : [
        {"title": "г Санкт-Петербург", "address_level": "region"},
        {"title": "р-н Петродворцовый", "address_level": "city_district"}
    ]
}
```

4. Индекс по адресу.
Запрос:
```fias.loc/api/mapping/?address=обл Псковская, р-н Новосокольнический, д Мошино```
Ответ:
{ "postal_code": 182200 }
