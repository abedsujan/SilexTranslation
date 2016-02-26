<?php
/**
 * @file
 *
 * @copyright Copyright (c) 2014 Palantir.net
 */

namespace MonoSolutions;

use Silex\Application as SilexApplication;

//translation
use Silex\Provider\TranslationServiceProvider;

class Application extends SilexApplication
{

    public function __construct()
    {
        parent::__construct();
        $this->registerProviders($this);
        $this->createRoutes($this);
    }

    protected function registerProviders(Application $app)
    {
        // Translator
        $app->register(new TranslationServiceProvider(), array(
            'locale_fallback' => 'da',
            'translation.class_path' => __DIR__ . '/../lib/vendor/symfony/src',
        ));
    }

    protected function createRoutes(Application $app)
    {

        $app->get('/', function () {
            $instruction = 'Mono Translation <br />
           <ul>
           <li>use following example routes: <ul>
            <li>/{lang_code}/{reseller_id}/{lang_message}/{name}</li>
            <li>/en/reseller_001/hello/Svinn</li>
            <li>/da/reseller_002/goodbye/Svinn</li>
            <li>/dkk/reseller_001/goodbye/Svinn (Fallback to Danish translation)</li>
           </ul></li>

           ';
            return $instruction;
        });

        $app['translator.domains'] = array(
            'reseller_001' => array(
                'en' => array(
                    'hello' => '[ Reseller_001 - Translation ] Hello %name%!  Welcome to MoNo',
                    'goodbye' => '[ Reseller_001 - Translation ] Goodbye %name%. See you soon.',
                ),
                'da' => array(
                    'hello' => '[ Reseller_001 - Translation ] Hej %name%. Velkommen til MoNo.',
                    'goodbye' => '[ Reseller_001 - Translation ] Favel %name%. Vi ses igen.',
                ),
                'fr' => array(
                    'hello' => '[ Reseller_001 - Translation ] Bonjour %name%',
                    'goodbye' => '[ Reseller_001 - Translation ] Au revoir %name%',
                ),
            ),
	        'reseller_002' => array(
		        'en' => array(
			        'hello' => 'Hi %name%!  Welcome to MoNo',
			        'goodbye' => 'Goodbye %name%. See you soon.',
		        ),
		        'da' => array(
			        'hello' => 'Hejjjj  %name%. Velkommen til MoNo.',
			        'goodbye' => 'Favel %name%. Vi ses igen.',
		        ),
		        'fr' => array(
			        'hello' => 'Bonjour Bonjour %name%',
			        'goodbye' => 'Au revoir %name%',
		        ),
	        ),
        );

        $app->get('/{_locale}/{reseller}/{message}/{name}', function ($reseller, $message, $name) use ($app) {

            return $app['translator']->trans($message, array('%name%' => $name), $reseller);
        });
    }
}
