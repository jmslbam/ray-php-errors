<?php

namespace JMSLBAM\RayPHPErrors;

class Ray
{
    /** @var bool */
    private $debug;

    public function init($debug = false)
    {

        $this->debug = $debug;

        if( ! class_exists('Spatie\Ray\Origin\Origin') ) {
            return;
        }

        set_exception_handler([$this, 'setExceptionHandler']); // exception
        set_error_handler([$this, 'setErrorHandler']); // error
        register_shutdown_function([$this, 'registerShutdownFunction']); // fatal error
    }

    /**
     * Set up error handling
     *
     * @return void
     */
    public function setErrorHandler($errorNumber, $message, $file, $lineNumber)
    {

        $payload = new ErrorPayload($message, $file.':'.$lineNumber);

        $this->sendRequest($message, $file, $lineNumber);

        if ($this->debug) {
            dump($message . PHP_EOL . $file.':'.$lineNumber);
        }

        return false;
    }

    /**
     * Catch fatal errors
     *
     * @return void
     */
    public function registerShutdownFunction()
    {

        $error = error_get_last();

        if (is_null($error)) {
            return;
        }

        $this->sendRequest($error['message'], $error['file'], $error['line']);

        if ($this->debug) {
            dump($error);
        }
    }

    /**
     * Catch exceptions
     *
     * @return void
     */
    public function setExceptionHandler($exception)
    {

        $this->sendRequest($exception->getMessage(), $exception->getFile(), $exception->getLine());

        if ($this->debug) {
            dump($exception);
        }
    }

    private function sendRequest($message, $file, $lineNumber, $color = 'red')
    {

        $payload = new ErrorPayload($message, $file.':'.$lineNumber);

        ray()->sendRequest($payload)->color($color);

        // Sprinkle a little WordPress Query Monitor here so that still shows the errors
        if (function_exists('do_action')) {
            do_action('qm/error', $message);
        }
    }
}
