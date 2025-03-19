# Ray PHP error logger

Pushes errors that normally would go into the `error.log` or `debug.log` straight into [Spatie's Ray](https://myray.app) debugger 

## Installation
`composer require jmslbam/ray-php-errors`

## Activation
Add `( new JMSLBAM\RayPHPErrors\Ray() )->init( true );` somewhere in your PHP code.

Passing `true` to the `init()` also shows the error on you screen. For example, with WordPress I pass `WP_DEBUG_DISPLAY` as argument.