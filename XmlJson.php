<?php

/**
 * @property-read string $attributePrefix
 * @property-read string $innerTextLable
 */
class XmlJson
{
    private $attributePrefix;
    private $innerTextLable;

    private function json(SimpleXMLElement $xmlElement)
    {
        $result = array();

        foreach ($xmlElement->children() as $child => $value) {
            $result[$child][] = $this->json($value);
        }

        foreach ($result as $key => $value) {
            if (count($result[$key]) == 1) {
                $result[$key] = $value[0];
            }
        }

        foreach ($xmlElement->attributes() as $attrName => $attrValue) {
            $result[$this->attributePrefix . $attrName] = (string)$attrValue;
        }

        $text = (string)trim(str_replace("\n", "", $xmlElement));
        if (!empty($text)) {
            if (count($result) == 0) {
                $result = $text;
            } else {
                $result[$this->innerTextLable] = $text;
            }
        }

        if (is_array($result)) {
            if (count($result) == 0) {
                $result = null;
            } else {
                foreach ($result as $key => $value) {
                    if (count($result[$key]) == 0) {
                        $result[$key] = null;
                    }
                }
            }
        }

        return $result;
    }

    private function xml(array $jsonItem, SimpleXMLElement $root, SimpleXmlElement $parent)
    {
        if (array_key_exists($this->innerTextLable, $jsonItem)) {
            $root[] = $jsonItem[$this->innerTextLable];
        }
        foreach ($jsonItem as $key => $value) {
            if (is_array($value)) {
                // $LOGGER_VARIABLES = $key;
                // LOGGER(__FILE__, __METHOD__, __LINE__, function () use ($LOGGER_VARIABLES) {
                //     echo ($LOGGER_VARIABLES);
                // });
                if (is_numeric($key)) {
                    if ($key == 0) {
                        $this->xml($value, $root, $parent);
                    } else {
                        $child = $parent->addChild($root->getName());
                        $this->xml($value, $child, $parent);
                    }
                } else {
                    $child = $root->addChild($key);
                    $this->xml($value, $child, $root);
                }
            } elseif ($key != $this->innerTextLable) {
                if (is_numeric($key)) {
                    if ($key == 0) {
                        $root[0] = $value;
                    } else {
                        $parent->addChild($root->getName(), $value);
                    }
                // } elseif ($key == $this->innerTextLable) {
                //     $root[] = $value;
                } elseif (substr($key, 0, strlen($this->attributePrefix)) == $this->attributePrefix) {
                    $root->addAttribute(substr($key, strlen($this->attributePrefix)), $value);
                } else {
                    $root->addChild($key, $value);
                }
                // $LOGGER_VARIABLES = [$key => $value, "Result" => str_replace("<", "&lt;", $parent->asXML())];
                // LOGGER(__FILE__, __METHOD__, __LINE__, function () use ($LOGGER_VARIABLES) {
                //     var_dump($LOGGER_VARIABLES);
                // });
            }
        }
    }

    public function __construct($attributePrefix = "@", $innerTextLable = "#text")
    {
        $this->attributePrefix = $attributePrefix;
        $this->innerTextLable = $innerTextLable;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'attributePrefix':
                return $this->attributePrefix;
                break;

            case "innerTextLable":
                return $this->innerTextLable;
                break;

            default:
                # code...
                break;
        }
    }

    public function __set($name, $value)
    {

    }

    /**
     * @param array $json
     * @param string $root
     * @return SimpleXMLElement
     */
    public function toXml(array $json, $root = "root")
    {
        $string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><$root></$root>";

        $rootElement = new SimpleXMLElement($string);

        $this->xml($json, $rootElement, $rootElement);

        return $rootElement;
    }

    /**
     * @param SimpleXMLElement $xml
     * @return array
     */
    public function toJson(SimpleXMLElement $xml)
    {
        return $this->json($xml);
    }
}

