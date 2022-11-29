<?php

namespace MondoTalk\Common;

use MondoTalk\Validation\JsonValidator;


class MondoTalkModel
{
    private $_propMap = array();

    protected static $credential;

    public static function setCredential($credential)
    {
        self::$credential = $credential;
    }

    /**
     * Default Constructor
     *
     * You can pass data as a json representation or array object. This argument eliminates the need
     * to do $obj->fromJson($data) later after creating the object.
     *
     * @param array|string|null $data
     * @throws \InvalidArgumentException
     */
    public function __construct($data = null)
    {
        switch (gettype($data)) {
            case "NULL":
                break;
            case "string":
                JsonValidator::validate($data);
                $this->fromJson($data);
                break;
            case "array":
                $this->fromArray($data);
                break;
            default:
        }
    }

    /**
     * Fills object value from Json string
     *
     * @param $json
     * @return $this
     */
    public function fromJson($json)
    {
        return $this->fromArray(json_decode($json, true));
    }

    /**
     * Fills object value from Array list
     *
     * @param $arr
     * @return $this
     */
    public function fromArray($arr)
    {
        if (!empty($arr)) {
            // Iterate over each element in array
            foreach ($arr as $k => $v) {
                // If the value is an array, it means, it is an object after conversion
                if (is_array($v)) {
                    // Determine the class of the object
                    if (($clazz = ReflectionUtil::getPropertyClass(get_class($this), $k)) != null) {
                        // If the value is an associative array, it means, its an object. Just make recursive call to it.
                        if (empty($v)) {
                            if (ReflectionUtil::isPropertyClassArray(get_class($this), $k)) {
                                // It means, it is an array of objects.
                                $this->assignValue($k, array());
                                continue;
                            }
                            $o = new $clazz();
                            //$arr = array();
                            $this->assignValue($k, $o);
                        } elseif (ArrayUtil::isAssocArray($v)) {
                            /** @var self $o */
                            $o = new $clazz();
                            $o->fromArray($v);
                            $this->assignValue($k, $o);
                        } else {
                            // Else, value is an array of object/data
                            $arr = array();
                            // Iterate through each element in that array.
                            foreach ($v as $nk => $nv) {
                                if (is_array($nv)) {
                                    $o = new $clazz();
                                    $o->fromArray($nv);
                                    $arr[$nk] = $o;
                                } else {
                                    $arr[$nk] = $nv;
                                }
                            }
                            $this->assignValue($k, $arr);
                        }
                    } else {
                        $this->assignValue($k, $v);
                    }
                } else {
                    $this->assignValue($k, $v);
                }
            }
        }
        return $this;
    }

}