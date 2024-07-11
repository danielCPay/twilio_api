<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Illuminate\Http\Request;

class TwlioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function createConversation()
    {
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $conversation = $twilio->conversations->v1->conversations->create([
            "friendlyName" => "My First Conversation",
        ]);

        print $conversation->sid;
    }
    public function fetchYourNewConversation()
    {
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $conversation = $twilio->conversations->v1
            ->conversations("CH0f99d9c32d2944c7a9b3a814b116d502")
            ->fetch();

        print $conversation->chatServiceSid;
    }
    public function addAnSmsParticipantToAConversation()
    {
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $participant = $twilio->conversations->v1
            ->conversations("CH0f99d9c32d2944c7a9b3a814b116d502")
            ->participants->create([
                "messagingBindingAddress" => "+51951442655",
                "messagingBindingProxyAddress" =>
                getenv("TWILIO_MY_NUMBER"),
            ]);

        print $participant->sid;
    }
    public function addAChatParticipantToAConversation()
    {

        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $participant = $twilio->conversations->v1
            ->conversations("CH0f99d9c32d2944c7a9b3a814b116d502")
            ->participants->create(["identity" => "testDeveloper"]);

        print $participant->sid;
    }
    public function sendAnSms(Request $request)
    {
        $datos = (array)$request->input();
        $recipients = $datos['recipients'];
        $body_sms = $datos['body_sms'];
        // Your Account SID and Auth Token from twilio.com/console
        // To set up environmental variables, see http://twil.io/secure
        $account_sid = getenv('TWILIO_ACCOUNT_SID');
        $auth_token = getenv('TWILIO_AUTH_TOKEN');
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

        // A Twilio number you own with SMS capabilities
        $twilio_number = getenv("TWILIO_MY_NUMBER");

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            '+51' . $recipients,
            array(
                'from' => $twilio_number,
                'body' => $body_sms
            )
        );
    }
    public function listAllMessageResources()
    {
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $messages = $twilio->messages->read([], 20);

        foreach ($messages as $record) {
            print $record->sid;
        }
    }
    public function fetchAMessage()
    {
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages("SMf1845bf9be30fcbe53823a1ff4639e9a")->fetch();

        print $message->to;
    }
}
