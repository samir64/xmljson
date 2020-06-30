<?php

/**
 * @property-read string $attributePrefix
 * @property-read string $innerTextLable
 */
class XmlJson
{
  private $childrenLabel;
  // private $attributePrefix;
  // private $innerTextLable;

  private function clean_json(array $json, $prefix = "")
  {
    $result = array();

    if (count($json) === 0) {
      $result = null;
    } else {
      foreach ($json as $key => $value) {
        if (is_array($value)) {
          $clean_json = $this->clean_json($value, ($key === $this->childrenLabel) ? $key : "");
        } else {
          $clean_json = $value;
        }

        if (count($json) === 1) {
          if (($key === $this->childrenLabel) || is_numeric($key)) {
            $result = $clean_json;
          } else {
            $result[$prefix . $key] = $clean_json;
          }
        } else {
          if ($key === $this->childrenLabel) {
            if (count($value) === 1) {
              $result[$key] = $clean_json;
            } else {
              foreach ($clean_json as $k => $v) {
                $result[$k] = $v;
              }
            }
          } else {
            if (!empty($prefix) && is_numeric($key)) {
              $result[$prefix] = $clean_json;
            } else {
              $result[$prefix . $key] = $clean_json;
            }
          }
        }
      }
    }

    return $result;
  }

  private function json(SimpleXMLElement $xmlElement)
  {
    $result = array(/* $this->childrenLabel => [] */);

    foreach ($xmlElement->children() as $child => $value) {
      $result[$this->childrenLabel][$child][] = $this->json($value);
    }

    foreach ($xmlElement->attributes() as $attrName => $attrValue) {
      $result[$attrName] = (string) $attrValue;
    }

    $text = (string) trim(str_replace("\n", "", $xmlElement));
    if (!empty($text)) {
      $result[$this->childrenLabel][] = $text;
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
      } else {
        if (is_numeric($key)) {
          if ($key == 0) {
            $root[0] = $value;
          } else {
            $parent->addChild($root->getName(), $value);
          }
          // } elseif ($key == $this->innerTextLable) {
          //     $root[] = $value;
        } elseif (strpos($key, $this->childrenLabel) === 0) {
          if ($key === $this->childrenLabel) {
            $root[] = $value;
          } else {
            $root->addChild(substr($key, strlen($this->childrenLabel)), $value);
          }
        } else {
          $root->addAttribute($key, $value);
          // $root->addAttribute(substr($key, strlen($this->childrenLabel)), $value);
        }
        // $LOGGER_VARIABLES = [$key => $value, "Result" => str_replace("<", "&lt;", $parent->asXML())];
        // LOGGER(__FILE__, __METHOD__, __LINE__, function () use ($LOGGER_VARIABLES) {
        //     var_dump($LOGGER_VARIABLES);
        // });
      }
    }
  }

  public function __construct($childrenLabel = "@")
  {
    $this->childrenLabel = $childrenLabel;
    // $this->attributePrefix = $attributePrefix;
    // $this->innerTextLable = $innerTextLable;
  }

  public function __get($name)
  {
    switch ($name) {
      case "childrenLabel":
        return $this->childrenLabel;
        break;

        // case 'attributePrefix':
        //     return $this->attributePrefix;
        //     break;

        // case "innerTextLable":
        //     return $this->innerTextLable;
        //     break;

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
   * @param string $root = "root"
   * @return SimpleXMLElement
   */
  public function toXml(array $json, $root = "root")
  {
    if (count($json) === 1) {
      foreach ($json as $key => $value) {
        $root = $key;
        $json = $value;
      }
    }

    $string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><$root></$root>";
    $rootElement = new SimpleXMLElement($string);
    $this->xml($json, $rootElement, $rootElement);

    return $rootElement;
  }

  /**
   * @param SimpleXMLElement $xml
   * @return array
   */
  public function toJson(SimpleXMLElement $xml, $clean = false)
  {
    $result = $this->json($xml);

    if ($clean) {
      $result = $this->clean_json($result);
    }

    return [$xml->getName() => $result];
  }
}