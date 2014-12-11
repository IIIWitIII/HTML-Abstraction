<?php

namespace Wit\Html;

class Element {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string|\Closure|Element|array
	 */
	protected $content;

	/**
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * @var bool
	 */
	protected $standalone = false;

	/**
	 * @var array
	 */
	protected $bindings = [];

	/**
	 * @var array
	 */
	protected static $standalones = [ "img", "input", "link", "br", "hr" /*, ...*/ ];

	/**
	 * creates a new HTML object
	 * @param string $name
	 * @param mixed $content string or Closure
	 * @access public
	 * @return Element
	 */
	public static function create( $name, $content = "" ) {
		return new Element( $name, $content, in_array( $name, self::$standalones ));
	}

	/**
	 * create the element
	 *
	 * @param string $name
	 * @param mixed $content string, closure or Element
	 * @param boolean $standalone
	 * @access public
	 */
	public function __construct( $name, $content = "", $standalone = false ) {
		$this->name = $name;
		$this->content = $content;
		$this->standalone = $standalone;
	}

	/**
	 * adds an attribute
	 *
	 * @param string $name
	 * @param string $value
	 * @return Element
	 */
	public function attribute( $name, $value ) {
		$this->attributes[$name] = $value;

		return $this;
	}

	/**
	 * transforms all params to their string form
	 *
	 * @access protected
	 * @return string
	 */
	protected function paramsToString() {
		$out = "";
		foreach( $this->attributes as $name => $value ) {
			$out .= " {$name}=\"{$value}\"";
		}
		return $this->standalone ? $out." /" : $out;
	}

	/**
	 * transform to string
	 *
	 * @access public
	 * @return string
	 */
	public function __toString() {
		$return =  "<{$this->name}{$this->paramsToString()}>";

		if( !$this->standalone ) {
			if(!is_array($this->content)) {
				$content = [ $this->content ];
			} else {
				$content = $this->content;
			}

			foreach($content as $c) {
				if( $c instanceOf \Closure ) {
					$return .= call_user_func_array( $c, $this->bindings );
				} else $return .= $c;
			}
			
			$return .= "</{$this->name}>";
		}

		return $return;
	}

	/**
	 * parameter binding for closures
	 *
	 * @access public
	 * @param $args
	 * @return Element
	 */
	public function bind(... $args) {
		$this->bindings = $args;
		return $this;
	}
}
