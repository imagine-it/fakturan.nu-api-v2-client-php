<?php	
	
if ( ! @include __DIR__ . '/../vendor/autoload.php' )
{
    die(<<<'EOT'
You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install --dev

You can then run tests by calling:

phpunit
EOT
       );
}

use Fakturan\Fakturan;


\VCR\VCR::configure()
	->enableRequestMatchers(array('method', 'url', 'host', 'body'));

\VCR\VCR::turnOn();

Fakturan::setup('jWE56VnOHqu-6HgaZyL2', 'LpdLorG0fmPRGOpeOvHSLiuloEHK0O8YsKliVPNY', [ 'sandbox' => true ]);