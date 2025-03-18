<?php

namespace JMSLBAM\RayPHPErrors;

class Ray {

	public function init() {
		set_exception_handler( [ $this, 'setExceptionHandler'] ); // exception
		set_error_handler( [ $this, 'setErrorHandler'] ); // error
		register_shutdown_function( [ $this, 'registerShutdownFunction'] ); // fatal error

		// For these scenarios I always just want to see Ray
		ray()->showApp();
	}

	/**
	 * Set up error handling
	 * 
	 * @return void
	 */
	public function setErrorHandler( $number, $message, $filepath, $line ) {

		$this->getFormatedErrorMessage($message, $filepath, $line, 'orange');
		
		return false;
	}

	/**
	 * Catch fatal errors
	 *
	 * @return void
	 */
	public function registerShutdownFunction() {
		
		$error = error_get_last();

		if( is_null( $error ) ) {
			return;
		}

		$this->getFormatedErrorMessage($error['message'], $error['file'], $error['line']);
	}

	/**
	 * Catch exceptions
	 *
	 * @return void
	 */
	public function setExceptionHandler( $exception ) {
		$this->getFormatedErrorMessage($exception->getMessage(), $exception->getFile(), $exception->getLine());
	}

	private function getFormatedErrorMessage( $message, $filepath, $line, $color = 'red' ) {
		$format = '%s <br><br> %s <br><br>';

		ray()->html( sprintf(
			$format,
			$message,
			$this->getFileLink( $filepath, $line )
		))->color( $color );
	}

	private function getFileLink( $filepath, $line ) {

		$filename = strtolower( basename( $filepath ) );

		$ahref = '<a href="vscode://file/' . $filepath . ':' . $line. '">' . $filename . ':' . $line . '</a>';

		return '<small style="text-decoration: underline;">' . $ahref . '</small>';
	}
}