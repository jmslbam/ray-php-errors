# Ray PHP error logger

Pushes errors that normally would go into the `error.log` or `debug.log` straigh into [Spatie's Ray](https://myray.app) debugger 

## Installation
`composer require jmslbam/ray-php-errors`

## Activation
Add `( new JMSLBAM\RayPHPErrors\Ray() )->init();` somewhere in your PHP code.