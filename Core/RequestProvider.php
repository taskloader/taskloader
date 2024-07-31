<?php

namespace TaskLoader\Core;


class RequestProvider implements RequestInterface {
	public string $documentRoot = '';
	public string $serverPort = '';
	public string $serverName = '';
	public string $requestUri = '';
	public string $requestMethod = '';
	public string $httpXRequestWith = '';
	public string $httpHost = '';
	public string $domain = '';
	public bool $ajax = false;

	public function __construct() {
		$this->importServerVars();
		$this->implementCustomMethods();
	}



	/**
	 * Import variables from $_SERVER
	 */
	private function importServerVars() : void
	{
		foreach( $_SERVER as $key => $value ) {
			$item = $this->toCamelCase($key);
			if (property_exists($this, $item)) $this->{$item} = $value;
		}
			//$this->{$this->toCamelCase($key)} = $value;

		$this->domain = property_exists($this, 'httpHost') ? $this->httpHost: $this->serverName;

		$this->ajax = (
			! empty($this->httpXRequestWith) // jQuery
				and strtolower($this->httpXRequestWith) == 'xmlhttprequest'
		);
	}


	/**
	 * Implements custom methods
	 */
	private function implementCustomMethods() : void
	{
		if ( ! $this->requestMethod == 'post' )
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
			case 'get':
				return [];
			break;

			case 'post':
				return filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);


			default:
				Exception::invalidMethod( $this->requestMethod  );
			break;
		}
	}
}
