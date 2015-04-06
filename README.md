HTML-Abstraction
================
_Requires PHP 5.6!_

Simple 'Hello World' example:
``` php
<?php
namespace Wit\Html;

include( 'Element.php' );

echo new Element("h1", "Hello World!");
echo new Element("div", [
	new Element("span", function() {
		return new Element("strong", "This is a");
	}),
	(new Element("span", function($str) {
		return $str;
	}))->bind(" simple HTML Abstraction!")->attribute("style", "color: red;")
]);

?>
```
