<?php 

namespace App\Http\Response;

/**
 * Api  Json response
 */
abstract class JsonResult implements \JsonSerializable {

    public function jsonSerialize() {
        return $this->removeNullValues(get_object_vars($this));
    }

    protected function removeNullValues($a) {
        $ret = (is_array($a) ? array() : new \stdClass());
        
        foreach ($a as $k => $v) {
            if (is_array($v) || is_object($v)) {
                if (is_object($ret))
                    $ret->$k = $this->removeNullValues($v);
                else
                    $ret[$k] = $this->removeNullValues($v);
            }
            else if (! is_null($v)) {
                if (is_object($ret))
                    $ret->$k = $v;
                else
                    $ret[$k] = $v;
            }
        }

        return $ret;
    }
}