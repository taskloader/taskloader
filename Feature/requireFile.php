<?php
namespace TaskLoader\Feature;

trait requireFile {

	/**
	 * Inject a file into the instance
	 *
	 * @param      string   $file   The file
	 *
	 * @return     boolean  File found
	 */
	protected function requireFile( string $file ) : void
	{
		require $file;
	}
}
