# XmlJson v1.0.0

Convert XML strings to array and JSON arrays to XML

It's realy easy to use!

## Functions

```php
function XmlJson(string $attributePrefix = "_", string $innerTextLable = "#value"); // Constructor

function toJson(SimpleXMLElement $xml): array;
function toXml(array $json, string $root = "root"): SimpleXMLElement;
```

## Convert method's table

| Pattern | XML                                                                  | JSON                                       |
| ------- | -------------------------------------------------------------------- | ------------------------------------------ |
| 1       | &lt;e/&gt;                                                           | "e": null                                  |
| 2       | &lt;e&gt;text&lt;/e&gt;                                              | "e": "text"                                |
| 3       | &lt;e name="value" /&gt;                                             | "e":{"@name": "value"}                     |
| 4       | &lt;e name="value"&gt;text&lt;/e&gt;                                 | "e": { "@name": "value", "#text": "text" } |
| 5       | &lt;e&gt; &lt;a&gt;text&lt;/a&gt; &lt;b&gt;text&lt;/b&gt; &lt;/e&gt; | "e": { "a": "text", "b": "text" }          |
| 6       | &lt;e&gt; &lt;a&gt;text&lt;/a&gt; &lt;a&gt;text&lt;/a&gt; &lt;/e&gt; | "e": { "a": ["text", "text"] }             |
| 7       | &lt;e&gt; text &lt;a&gt;text&lt;/a&gt; &lt;/e&gt;                    | "e": { "#text": "text", "a": "text" }      |

## Sample

### Make instance

```php
$xmlJson = new XmlJson();
```

### Sample XML

```php
$xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<bookstore>
  <book category="cooking">
    <title lang="en">Everyday Italian</title>
    <author>Giada De Laurentiis</author>
    <year>2005</year>
    <price>30.00</price>
  </book>
  <book category="children">
    <title lang="en">Harry Potter</title>
    <author>J K. Rowling</author>
    <year>2005</year>
    <price>29.99</price>
  </book>
  <book category="web">
    <title lang="en">XQuery Kick Start</title>
    <author>James McGovern</author>
    <author>Per Bothner</author>
    <author>Kurt Cagle</author>
    <author>James Linn</author>
    <author>Vaidyanathan Nagarajan</author>
    <year>2003</year>
    <price>49.99</price>
  </book>
  <book category="web" cover="paperback">
    <title lang="en">Learning XML</title>
    <author>Erik T. Ray</author>
    <year>2003</year>
    <price>39.95</price>
  </book>
</bookstore>
XML;
```

### Convert XML string to SimpleXMLElement

```php
$xmlDoc = new SimpleXMLElement($xmlString)
```

### Convert XML document to JSON array

```php
$jsonArr = $xmlJson->toJson($xmlDoc);

//Result:
/*
{
    "book": [
        {
            "title": {
                "@lang": "en",
                "#text": "Everyday Italian"
            },
            "author": "Giada De Laurentiis",
            "year": "2005",
            "price": "30.00",
            "@category": "cooking"
        },
        {
            "title": {
                "@lang": "en",
                "#text": "Harry Potter"
            },
            "author": "J K. Rowling",
            "year": "2005",
            "price": "29.99",
            "@category": "children"
        },
        {
            "title": {
                "@lang": "en",
                "#text": "XQuery Kick Start"
            },
            "author": [
                "James McGovern",
                "Per Bothner",
                "Kurt Cagle",
                "James Linn",
                "Vaidyanathan Nagarajan"
            ],
            "year": "2003",
            "price": "49.99",
            "@category": "web"
        },
        {
            "title": {
                "@lang": "en",
                "#text": "Learning XML"
            },
            "author": "Erik T. Ray",
            "year": "2003",
            "price": "39.95",
            "@category": "web",
            "@cover": "paperback"
        }
    ]
}
*/
```

### Convert JSON array to XML document (SimpleXMLElement)

```php
$xmlDoc_revert = $xmlJson->toXml($jsonArr, "bookstore");

/*
<?xml version="1.0" encoding="UTF-8"?>
<bookstore>
    <book category="cooking">
        <title lang="en">Everyday Italian</title>
        <author>Giada De Laurentiis</author>
        <year>2005</year>
        <price>30.00</price>
    </book>
    <book category="children">
        <title lang="en">Harry Potter</title>
        <author>J K. Rowling</author>
        <year>2005</year>
        <price>29.99</price>
    </book>
    <book category="web">
        <title lang="en">XQuery Kick Start</title>
        <author>James McGovern</author>
        <author>Per Bothner</author>
        <author>Kurt Cagle</author>
        <author>James Linn</author>
        <author>Vaidyanathan Nagarajan</author>
        <year>2003</year>
        <price>49.99</price>
    </book>
    <book category="web" cover="paperback">
        <title lang="en">Learning XML</title>
        <author>Erik T. Ray</author>
        <year>2003</year>
        <price>39.95</price>
    </book>
</bookstore>
*/
```
