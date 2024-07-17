<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
//////API CONVERSATIONS///////////////
$router->get('/create/conversation/twilio', 'TwlioController@createConversation');
$router->get('/fetch/conversation/twilio', 'TwlioController@fetchYourNewConversation');
$router->get('/add/participant/twilio', 'TwlioController@addAnSmsParticipantToAConversation');
$router->get('/add/participant/chat/twilio', 'TwlioController@addAChatParticipantToAConversation');
$router->get('/list//all/conversation/twilio', 'TwlioController@listAllConversationMessages');
$router->post('/create/conversation/message/twilio', 'TwlioController@createConversationMessage');
////////API SEND SMS///// 
$router->post('/send/sms/twilio', 'TwlioController@sendAnSms');
$router->get('/list/all/sms/twilio', 'TwlioController@listAllMessageResources');
$router->get('/fetch/sms/twilio', 'TwlioController@fetchAMessage');
$router->get('/list/sms/criteria/twilio', 'TwlioController@listMessageResourcesMatchingFilterCriteria');
$router->get('/list/sms/before/specific/date/twilio', 'TwlioController@listMessagesThatWereSentBeforeSpecificDate');
$router->get('/list/sms/before/specific/time/period/twilio', 'TwlioController@listMessagesWithinSpecificTimePeriod');