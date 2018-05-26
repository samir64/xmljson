# XmlJson v1.0.0

Convert XML strings to array and JSON arrays to XML

It's realy easy to use!

## Functions

```php
function XmlJson($attributePrefix = "_", $innerTextLable = "#value"); // Constructor

function toJson(SimpleXMLElement $xml);
function toXml(array $json, $root = "root");
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
```

### Convert JSON array to XML document (SimpleXMLElement)

```php
$xmlDoc_revert = $xmlJson->toXml($jsonArr, "bookstore");
```
