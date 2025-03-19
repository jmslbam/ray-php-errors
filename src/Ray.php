<?php

namespace JMSLBAM\RayPHPErrors;

class Ray {

	public function init() {
		set_exception_handler( [ $this, 'setExceptionHandler'] ); // exception
		set_error_handler( [ $this, 'setErrorHandler'] ); // error
		register_shutdown_function( [ $this, 'registerShutdownFunction'] ); // fatal error
	}

	/**
	 * Set up error handling
	 * 
	 * @return void
	 */
	public function setErrorHandler( $errorNumber, $message, $file, $lineNumber ) {

		$payload = new ErrorPayload($message, $file . ':' . $lineNumber );

		$this->sendRequest( $message, $file, $lineNumber );

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

		$this->sendRequest( $error['message'], $error['file'], $error['line'] );

		return false;
	}

	/**
	 * Catch exceptions
	 *
	 * @return void
	 */
	public function setExceptionHandler( $exception ) {

		$this->sendRequest( $exception->getMessage(), $exception->getFile(), $exception->getLine() );
		
		return false;
	}

	private function sendRequest( $message, $file, $lineNumber, $color = 'red' ) {

		$payload = new ErrorPayload($message, $file . ':' . $lineNumber );
		
		ray()->sendRequest( $payload )->color( $color );

		// Sprinkle a little WordPress Query Monitor here so that still shows the errors
		if( function_exists('do_action') ) {
			do_action( 'qm/error', $message );
		}
	}
}
