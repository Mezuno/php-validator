# Requests & Rules Validator

> Документация к RulesValidator

___

Оглавление

1. [Метод rules()](#rules)
   - [Правила](#availableRules)
2. [Методы правил](#rulesMethods)
   - [required() и nullable()](#requiredAndNullable)
   - [min() и max()](#minAndMax)
   - [items()](#items)
   - [existsValidator()](#existsValidator)
   - [type()](#type)
3. [Метод validationException()](#validationExceptions)
4. [Метод validate()](#validate)

___

Каждый Request может содержать 3 метода для обработки входящих данных:
```php

class SomeRequest extends \Kernel\Request {

    protected function rules(): array
    {
        // Ваши правила валидации полей
    }
    
    protected function validationExceptions(): array
    {
        // Кастомные сообщения об ошибках валидации
    }
    
    public function validate(): void
    {
        // Дополнительная валидация и преобразование после правил
    }
}
```
___
## <a name="rules"></a>Метод rules()


Возвращает массив правил валидации входящих полей

Правила представляют собой классы с постфиксом Rules
>Пример IntRules для some_integer_field:
```php
protected function rules(): array
{
    return [
        'some_integer_field' => IntRules::make();
    ];
}
```

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
protected function rules(): array
{
    'some_field' => IntRules::make()->required(),
    'another_field' => IntRules::make()->nullable(),
}
```

> [!WARNING]
required() и nullable() взаимоисключающие правила <br>
Это значит, что нельзя указывать и то и другое, иначе применится последнее 

### <a name="minAndMax"></a>min() и max()

> Все правила типа NumericRules имеют методы min() и max()

Эти методы принимают парамтером минимальное и максимальное значение поля соответственно

> Пример:
```php
protected function rules(): array
{
    return [
        'some_integer_field' => IntRules::make()->min(1)->max(3),
    ];
}
```
___

Также, у StringRules есть такие же методы, но имеют другое значение.
Если в случае с NumericRules min() и max() обозначают конкретные значения,
то в случае со строкой min() и max() обозначают ограничение на длину строки.

> Пример:
```php
protected function rules(): array
{
    return [
        'password' => StringRules::make()->min(8)->max(32),
    ];
}
```

### <a name="items"></a>items()

> ArrayRules содержит метод items(), принимающий в себя
> правила валидации полей массива, или полей подмассивов

Метод items() у ArrayRules имеет два параметра: первый
параметр - массив правил для валидации полей массива, второй -
флаг $nested, который указывает на наличие вложенности

> Пример массива с одним обязательным полем:
```php
protected function rules(): array
{
    return [
        'some_array_field' => ArrayRules::make()->items([
            'some_array_item' => StringRules::make()->required(),
        ]),
    ];
}
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
protected function rules(): array
{
    return [
        'some_array_field' => ArrayRules::make()->items([
            'some_nested_array_item' => IntRules::make()->required(),
        ], true),
    ];
}
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

### <a name="existsValidator"></a>existsValidator()
> Этот метод имеет только ExistsRules.

Принимает в себя класс репоизтория первым параметром, и метод этого
репозитория вторым параметром.

> Пример:
```php
protected function rules(): array
{
    return [
        'card_barcode' => ExistsRules::make()->existsValidator(CardRepository::class, 'findByBarcode'),
    ];
}
```

### <a name="type"></a>type()
> Этот метод имеет только ExistsRules.

Необходим для указания типа поля, которое должно существовать в БД.

> Пример:
```php
protected function rules(): array
{
    return [
        'card_barcode' => ExistsRules::make()
            ->type(IntRules::class)
            ->existsValidator(CardRepository::class, 'findByBarcode'),
    ];
}
```

> [!WARNING]
При указании типа таким образом, нельзя указать дополнительные правила.
Например: если поле должно быть строкой, нельзя указать длину этой строки.

## <a name="validationExceptions"></a>Метод validationExceptions()

> Метод validationExceptions() нужен для установки кастомных сообщений на ошибки валидации

Чтобы задать кастомный Exception для какого-либо правила, нужно просто указать в возвращаемом
массиве ключ поля, ключ правила и сам Exception

> Пример:
```php
protected function validationExceptions(): array
{
    return [
        'field' => [
            'required' => new CustomException('Some custom message for required');
            'type' => new CustomException('Some custom message for type');
            'min' => new CustomException('Some custom message for min');
            'max' => new CustomException('Some custom message for max');
            'exists' => new CustomException('Some custom message for exists');
        ],
        
        'phone_field' => [
            'phone' => new CustomException('Some custom message for phone');
        ],
        
        'email_field' => [
            'email' => new CustomException('Some custom message for email');
        ],
    ];
}
```

В данном примере при непереданном в запросе поле field будет выброшен указанный Exception.

___

Также, в можно указать кастомные Exception для элементов массивов, следующим образом:

```php
protected function validationExceptions(): array
{
    return [
        'product' => [
            'items' => [
                'price' => new CustomException('Item product[price] required.');                        
            ],
        ],
    ];
}
```

Если необходимо добавить кастомное сообщение для элемента вложенного массива, используйте ключ nested_items:

```php
protected function validationExceptions(): array
{
    return [
        'products' => [
            'nested_items' => [
                'price' => new CustomException('Item products[][price] required.');                        
            ],
        ],
    ];
}
```

## Метод <a name="validate"></a>validate()

Здесь происходит любая дополнительная валидация после правил. <br>
Если по какой-то причине правил валидации не хватило, поля можно довалидировать здесь. <br>


Здесь досутпно два массива данных

1. `$this->data[]` - массив входящих данных
2. `$this->validated[]` - массив отвалидированных входящих данных

```php
public function validate(): void
{
    if ($this->validated['some_flag']) {
        // Ваша логика
    }
}
```

Также, здесь можно отформатировать или как-либо преобразовать входящие данные.
```php

public function validate(): void
{
    $this->validated['new_field'] =
        (float)$this->validated['some_integer_field'];
        
    unset($this->validated['some_integer_field']);
}
```

После всех махинаций с данными массива `$this->validated[]`, его можно будет получить в контроллере
при помощи метода `$request->validated()`

Чтобы получить конкретное поле можно указать ключ `$request->validated('some_integer_field')`
