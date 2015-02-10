<?php
namespace Blender\Client;

class UserRoutePricing {
    private static $props = null;
    private $price;
    private $countryCode;
    private $countryName;
    private $description;
    private $userRouteId;

    function __construct($node) {
        $this->fromXmlNode($node);
    }

    public function fromXmlNode($node) {
        $propnames = UserRoutePricing::getProps();

        foreach ($node->childNodes as $child) {
            $prop = $child->nodeName;
            if (in_array($prop, $propnames)) {

                $this->$prop = $child->nodeValue;
            }
        }
    }

    private static function getProps() {
        if (UserRoutePricing::$props == null) {
            $vars = get_class_vars(__CLASS__);
            UserRoutePricing::$props = array_keys($vars);
        }
        return UserRoutePricing::$props;
    }

    public function hasPrefix($prefix) {
        $arr = explode(",", $this->countryCode);
        return (in_array($prefix, $arr));
    }

    /**
     * @return mixed
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

    /**
     * @return mixed
     */
    public function getCountryName() {
        return $this->countryName;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getUserRouteId() {
        return $this->userRouteId;
    }


}