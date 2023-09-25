# Rules Validator

> Документация к RulesValidator

___

Оглавление

1. [Методы правил](#rulesMethods)
   - [required() и nullable()](#requiredAndNullable)
   - [min() и max()](#minAndMax)
   - [items()](#items)
   - [exists()](#exists)
   - [type()](#type)

___


### <a name="availableRules"></a> Правила

На текущий момент (28.08.2023) существуют следующие правила для валидации:

| Класс       | Значение      | Валидно                                                   |
|-------------|---------------|-----------------------------------------------------------|
| IntRules    | Целочисленное | ... -1, 0, 1, ...                                         |
| FloatRules  | Дробное       | ... -1.0, 0.0, 1 ...                                      |
| BoolRules   | Булево        | true, false, "true", "false", "on", "off", 1, 0, "1", "0" |
| StringRules | Строка        | "Some string"                                             |
| ArrayRules  | Массив        | [1, 2, "три"]                                             |


И производные правила

| Класс       | Значение        | Валидно                          |
|-------------|-----------------|----------------------------------|
| DateRules   | Дата и время    | "2023-08-03 11:38:06"            |
| PhoneRules  | Телефон         | "+79998887766"                   |
| EmailRules  | Почта           | "example@mail.ru"                |
| ExistsRules | Существует в БД | Любое поле, если существует в БД |

___
## <a name="rulesMethods"></a>Методы правил

### <a name="requiredAndNullable"></a>required() и nullable()

> У каждого правила существует минимум 2 метода: required() и nullable()

required() отвечает за обязательность поля

nullable() отвечает за обязательность и возможность поля быть null

> [!NOTE]
По умолчанию поле не обязательное


```php
$rules = [
    'some_field' => IntRules::make()->required(),
    'another_field' => IntRules::make()->nullable(),
];
```

> [!WARNING]
required() и nullable() взаимоисключающие правила <br>
Это значит, что нельзя указывать и то и другое, иначе применится последнее 

### <a name="minAndMax"></a>min() и max()

> Все правила типа NumericRules имеют методы min() и max()

Эти методы принимают парамтером минимальное и максимальное значение поля соответственно

> Пример:
```php
$rules = [
     'some_integer_field' => IntRules::make()->min(1)->max(3),
];
```
___

Также, у StringRules есть такие же методы, но имеют другое значение.
Если в случае с NumericRules min() и max() обозначают конкретные значения,
то в случае со строкой min() и max() обозначают ограничение на длину строки.

> Пример:
```php
$rules = [
     'password' => StringRules::make()->min(8)->max(32),
];
```

### <a name="items"></a>items()

> ArrayRules содержит метод items(), принимающий в себя
> правила валидации полей массива, или полей подмассивов

Метод items() у ArrayRules имеет два параметра: первый
параметр - массив правил для валидации полей массива, второй -
флаг $nested, который указывает на наличие вложенности

> Пример массива с одним обязательным полем:
```php
$rules = [
     'some_array_field' => ArrayRules::make()->items([
         'some_array_item' => StringRules::make()->required(),
     ]),
];
```

Валидно:
```json
{
  "some_array_field": {
    "some_nested_array_item": 2
  }
}
```

Невалидно:
```json
{
  "some_another_array_field": [
    {
      "some_nested_array_item": 2
    }
  ]
}
```
___

> Пример массива с валидацией вложенных массивов

> **Note**
Второй параметр при валидации вложенных массивов - true

```php
$rules = [
     'some_array_field' => ArrayRules::make()->items([
         'some_nested_array_item' => IntRules::make()->required(),
     ], true),
];
```

Валидно:
```json
{
  "some_another_array_field": [
    {
      "some_nested_array_item": 2
    }
  ]
}
```

Невалидно:
```json
{
  "some_array_field": {
    "some_nested_array_item": 2
  }
}
```

### <a name="exists"></a>exists()

Принимает в себя класс репоизтория первым параметром, и метод этого
репозитория вторым параметром.

> Пример:
```php
$rules = [
  'card_barcode' => StringRules::make()->exists(CardRepository::class, 'findByBarcode'),
];
```

## <a name="validationExceptions"></a>Метод validationExceptions()

> Метод validationExceptions() нужен для установки кастомных сообщений на ошибки валидации

Чтобы задать кастомный Exception для какого-либо правила, нужно просто указать в возвращаемом
массиве ключ поля, ключ правила и сам Exception

> Пример:
```php
$messages = [
     'field.required' => 'Some custom message for required',
     'field.type' => 'Some custom message for type'
     'field.min' => 'Some custom message for min'
     'field.max' => 'Some custom message for max'
     'field.exists' => 'Some custom message for exists'
     
     'phone_field.phone' => 'Some custom message for phone';
     
     'email_field.email' => 'Some custom message for email';
];
```

В данном примере при непереданном в запросе поле field будет выброшен указанный Exception.

___

Также, в можно указать кастомные Exception для элементов массивов, следующим образом:

```php
$rules = [
     'product.items.price' => 'Item product[price] required.';
];
```

Если необходимо добавить кастомное сообщение для элемента вложенного массива, используйте ключ nested_items:

```php
$rules = [
     'products.nested_items.price' => 'Item products[][price] required.';
];
```

