<?php
/**
 * @file
 *
 * @copyright Copyright (c) 2016 abedsujan.com
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
            <li>/{lang_code}/{lang_message}/{name}</li>
            <li>/en/hello/Svinn</li>
            <li>/da/goodbye/Svinn</li>
            <li>/dkk/goodbye/Svinn (Fallback to Danish translation)</li>
           </ul></li>

           ';
            return $instruction;
        });


        $app['translator.domains'] = array(
            'messages' => array(
                'en' => array(
                    'hello' => 'Hello %name%!  Welcome to MoNo.',
                    'goodbye' => 'Goodbye %name%. See you soon.',
                ),
                'da' => array(
                    'hello' => 'Hej %name%. Velkommen til MoNo.',
                    'goodbye' => 'Favel %name%. Vi ses igen.',
                ),
                'fr' => array(
                    'hello' => 'Bonjour %name%',
                    'goodbye' => 'Au revoir %name%',
                ),
            ),
        );

        $app->get('/{_locale}/{message}/{name}', function ($message, $name) use ($app) {

            return $app['translator']->trans($message, array('%name%' => $name));
        });

    }
}
