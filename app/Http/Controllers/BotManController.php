<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\Drivers\Web\WebDriver;
use App\Conversations\LeBoncoinExpertConversation; 

class BotManController extends Controller
{
    public function handle()
    {
        DriverManager::loadDriver(WebDriver::class);

        $config = [
            'web' => [
                'matchingData' => [
                    'driver' => 'web',
                ],
            ],
            'botman' => [
                'conversation_cache_time' => 30,
            ],
        ];

        $botman = BotManFactory::create($config, new LaravelCache());

        
        $botman->hears('bonjour|hello|start|commencer', function (BotMan $bot) {
            $bot->startConversation(new LeBoncoinExpertConversation());
        });

        
        $botman->fallback(function($bot) {
            $bot->startConversation(new LeBoncoinExpertConversation());
        });

        $botman->listen();
    }
}