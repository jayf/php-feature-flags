<?php
/*  PHP Feature Flags
    https://github.com/jayf/php-feature-flags
    MIT License 2013
*/

/*  USAGE

    1. require this file in your own code:

        require_once '/your/path/feature-flags.php';

    2. in your code, create a feature flag variable
    (name is your choice), and initialize:

        either (one step):

        $FF = new FeatureFlags(
            array(
                'cookie'=>'FF',
                'uriParam'=>'ff',
                'ip'=>'10.0.0.0'
                )
            );

        OR (two steps):

        $FF = new FeatureFlags();
        $FF->setDetect(
            array(
                'cookie'=>'FF',
                'uriParam'=>'ff',
                'ip'=>'10.0.0.0')
            );

        (see the README for more info on the detect options)

    3. in your code, test for any flag, like so:

        $FF->isFlagged();
        // returns true when any flag is set

    4. in your code, test for specific flags, like so:

        $FF->hasFlag('dog');
        // returns true when the "dog" flag is set

    5. you also can see if a flag method is active, like so:

        $FF->usesMethod('cookie')
        // returns true when flag cookie detected
*/

class FeatureFlags {
    private $isFlagged = false;
    private $methods = array();
    private $flags =    array();

    function __construct($options) {
        $this->setDetect($options);
    }


    function setDetect($options) {

        $init = array(
                    'cookie' => null,
                    'ip' => null,
                    'uriParam' => null
                );

        $detect = array_merge($init, $options);

        $this->detectCookie($detect['cookie']);
        $this->detectIp($detect['ip']);
        $this->detectUriParam($detect['uriParam']);
    }

    private function detectCookie($cookiename) {
        if(isset($cookiename)) {
            /*
                detect cookie-based flag
                detects when cookie exists
                and comma-separated values in the cookie:
                value1,value2,value3
            */

            if (isset($_COOKIE[$cookiename])) {
                $this->isFlagged = true;
                $this->addMethod('cookie');
                $vals = explode(',', $_COOKIE[$cookiename]);
                foreach ($vals as $val) {
                    $this->addFlag($val);
                }
            }
        }
    }

    private function detectIp($ip) {
        if(isset($ip) && $ip === $_SERVER['REMOTE_ADDR']) {
            /*
                detect IP Address-based flag
                detects when requestor's IP Address matches
            */
            $this->isFlagged = true;
            $this->addMethod('ip');
        }
    }

    private function detectUriParam($param) {
        if(isset($param)) {
            /*
                detect URI Query Parameter-based flag
                detects when parameter exists
                and comma-separated values in the parameter:
                value1,value2,value3
            */
            if (isset($_GET[$param])) {
                    $this->isFlagged = true;
                    $this->addMethod('uriParam');
                    $vals = explode(',', $_GET[$param]);
                    foreach ($vals as $val) {
                        $this->addFlag($val);
                    }
              }
        }
    }

    private function addFlag($value) {
        /*
            adds a specific flag value
            based on cookie or URI query parameter

        */
        $this->flags[]  = $value;
    }

    function hasFlag($value) {
        /*
            returns true if a specific flag is set
        */
        return in_array($value, $this->flags);
    }

    function isFlagged() {
        /*
            returns true if any flag is detected
        */
        return $this->isFlagged;
    }

    private function addMethod($value) {
        /*
            sets specific flag values
            based on cookie or URI query parameter

        */
        $this->methods[]  = $value;
    }

    function usesMethod($value) {
        /*
            returns true if any flag was set by this method
        */
        return in_array($value, $this->methods);
    }
}
?>
