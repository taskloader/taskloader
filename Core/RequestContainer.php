<?php

namespace TaskFiber\Core;


class RequestContainer implements RequestInterface {

	public function __construct() {
		$this->importServerVars();
		$this->implementCustomMethods();
	}


	
	/**
	 * Import variables from $_SERVER
	 */
	private function importServerVars() : void
	{
		foreach( $_SERVER as $key => $value )
			$this->{$this->toCamelCase($key)} = $value;

		$this->domain = property_exists($this, 'httpHost') ? $this->httpHost: $this->serverName;

		$this->ajax = (
			property_exists($this, 'httpXRequestWith') // jQuery
				and strtolower($this->httpXRequestWith) == 'xmlhttprequest'
		or
			property_exists($this, 'xRequestWith') // Angular
				and strtolower($this->xRequestWith) == 'xmlhttprequest'
		);
	}


	/**
	 * Implements custom methods
	 */
	private function implementCustomMethods() : void
	{
		if ( ! $this->requestMethod == 'POST' )
			return;
		

		if ( ! array_key_exists('_method', $_POST) )
			return;

		$this->requestMethod = strtoupper($_POST['_method']);
		unset($_POST['_method']);
	}


	/**
	 * Returns camelcase formatted string
	 *
	 * @param      string  $string  The string
	 *
	 * @return     string  String in camelcase format
	 */
	public function toCamelCase( string $string ) : string
	{
		$string = strtolower($string);

		preg_match_all('/_[a-z]/', $string, $parts);

		foreach( $parts[0] as $part ) {
			$name = str_replace('_', '', strtoupper($part));
			$string = str_replace($part, $name, $string);
		}

		return $string;
	}


	/**
	 * Gets the body.
	 *
	 * @return     array  The body.
	 */
	public function getBody() : array
	{

		switch ( $this->requestMethod ) {
			case 'GET':
				return [];
			break;

			case 'POST':
				return filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

			
			default:
				Exception::invalidMethod( $this->requestMethod  );
			break;
		}
	}
}