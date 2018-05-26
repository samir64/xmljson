# XmlJson v1.0.0

Convert XML strings to array and JSON arrays to XML

It's realy easy to use!

## Functions

```php
function XmlJson(string $attributePrefix = "_", string $innerTextLable = "#value"); // Constructor

function toJson(SimpleXMLElement $xml): array;
function toXml(array $json, string $root = "root"): SimpleXMLElement;
```

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
                "_lang": "en",
                "#value": "Everyday Italian"
            },
            "author": "Giada De Laurentiis",
            "year": "2005",
            "price": "30.00",
            "_category": "cooking"
        },
        {
            "title": {
                "_lang": "en",
                "#value": "Harry Potter"
            },
            "author": "J K. Rowling",
            "year": "2005",
            "price": "29.99",
            "_category": "children"
        },
        {
            "title": {
                "_lang": "en",
                "#value": "XQuery Kick Start"
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
            "_category": "web"
        },
        {
            "title": {
                "_lang": "en",
                "#value": "Learning XML"
            },
            "author": "Erik T. Ray",
            "year": "2003",
            "price": "39.95",
            "_category": "web",
            "_cover": "paperback"
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
