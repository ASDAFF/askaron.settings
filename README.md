# askaron.settings

**Описание**

Страница дополнительных настроек для своего сайта или Битрикс24 в коробке.

Получить настройку в коде сайта, можно как обычную опцию, и модуль подключать не нужно:

`<?echo \COption::GetOptionString( "askaron.settings", "UF_PHONE" );?>`


Дополнительно модуль содержит активити, которое позволяет использовать "Настройки++" в бизнес-процессах в Битрикс24 в коробке.

Все настройки хранятся в отдельной таблице и не ограничены длинной опции в 2000 символов.

Модуль основан на дополнительных пользовательских полях Битрикса. Уже есть самые разнообразные типы полей, такие как:

- многострочное поле ввода, которое может превысить 2000 символов.
- привязка к элементу инфоблока
- значение из списка
- файл
- и много-много других уже готовых битриксовских типов полей

Программно записать значение настройки из своего скрипта можно методом
CAskaronSettings::Update

Полное название модуля «Настройки плюс плюс».

**Установка**

После установки перейдите на страницу настроек модуля и создайте собственные настройки:
Настройки -> Настройки++ (в самом низу)

Настройки добавляются, как обычные пользовательские поля.

Получить настройку из кода сайта, можно как обычную опцию и модуль подключать не нужно:
<?echo COption::GetOptionString( "askaron.settings", "UF_PHONE" );?>
Программно записать настройку из своего скрипта можно методом CAskaronSettings::Update.

Примеры, описание методов и события есть в документации по модулю:
http://askaron.ru/api_help/course1/chapter0102/