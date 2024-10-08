<?php
namespace TaskLoader\Feature;

trait loadFile {

	/**
	 * Inject a file into the instance
	 *
	 * @param      string   $file   The file
	 *
	 * @return     boolean  File found
	 */
	protected function loadFile( string $file ) : bool
	{
		if ( file_exists( $file ) ) {
			require $file;
			return true;
		}

		return false;
	}
}
