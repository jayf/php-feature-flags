PHP Feature Flags
=================

A simple PHP class for setting feature flags via HTTP cookie, URI query parameter or IP address.


##What are Feature Flags

A *Feature Flag* is a setting you use to turn a feature on or off. This is often used to turn on experimental features in your code, e.g., for testing.

###Example

```php
if ( $FF->hasFlag('crazy') ) {
    doCrazyExperiment();
} else {
    doReallySafeStuff();
}
```

##And This Respository Is...

This is a PHP class that reads feature flags in URL query strings, browser cookies, and/or client IP address. These are only "on" flags--they do not represent values or states beyond "on".

The idea is: when a flag is absent, it is *off*, When a flag is present, it is *on*.

The function then allows you to test for specific flags being present / on, using the above **$ff->hasFlag('your-flag-name')** syntax.

Note that this library doesn't help you set flags per se--it just reads them.


##Basic Usage

Require this file in your own code:

```php
    require_once '/your/path/feature-flags.php';
```

In your code, create a feature flag variable (name is your choice), and initialize:

either (one step):

```php
    $FF = new FeatureFlags(
        array(
            'cookie'=>'FF',
            'uriParam'=>'ff',
            'ip'=>'10.0.0.0'
            )
        );
```

OR (two steps):

```php
    $FF = new FeatureFlags();
    $FF->setDetect(
        array(
            'cookie'=>'FF',
            'uriParam'=>'ff',
            'ip'=>'10.0.0.0')
        );
```

*see [Detect Options](#detect-options), below*

In your code, test for any flag, like so:

```php
    $FF->isFlagged();
    // returns true when any flag is set
```

In your code, test for specific flags, like so:

```php
    $FF->hasFlag('dog');
    // returns true when the "dog" flag is set
```

You also can see if a flag method is active, like so:

```php
    $FF->usesMethod('cookie')
    // returns true when flag cookie detected
```

##Detect Options

In order to detect any flags, you need to tell the class where to look, using an array of options, for example:

```php
    $FF = new FeatureFlags(
        array(
            'cookie'=>'FF',
            'uriParam'=>'ff',
            'ip'=>'10.0.0.0'
            )
        );
```

These represent "places" to look for flags: HTTP cookie, URL query string and (the requestor) IP Address. Each of these is optional, but the class obviously won't find any flags unless you give it at least one place to look.

This works:

```php
    $FF = new FeatureFlags(array('cookie'=>'FF'));
```

As does this:
```php
    $FF = new FeatureFlags(array('cookie'=>'FF','ip'=>'10.0.0.0'));
```

Note that these are used to indicate that the class should:

* look for a cookie named FF
* look for URL query parameters named ff
* look for a client with an IP address of 10.0.0.0

Each method is optional, but if you don't include any method


##Flag Syntax

In addition to recognizing that a general flag is present via a cookie, URL query string, or IP address, this class will parse the actual values of the cookie and/or query string, and set these values as specific flags.

Comma-separated values are recognized in both cookies and query strings.


Syntax examples:

###Query Strings

All valid:

````
?ff=Kurt
?ff=Elvin,Ringo
````

###Cookies

All valid values:

````
Kurt
Elvin,Ringo
````

## See Also: Javascript Feature Flags

I've also created a simple Feature Flags fucntion in Javascript, see [Javascript Feature Flags](https://github.com/jayf/php-feature-flags). *Note that these two libraries don't have 100% matching flag syntax--that's a TODO. Currently, the PHP library's flag syntax is subset of the JS's.*
