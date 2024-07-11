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

    /**
     * Conversation Api.
     *
     */
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

    # SMS SEND API
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
            # print $record->sid;
            var_dump($record->body);
        }
    }
    public function fetchAMessage()
    {
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages("SM6d827718023d827af3aef518d4a30fd8")->fetch();

        print $message->to;
    }
    public function listMessageResourcesMatchingFilterCriteria(Request $request)
    {
        $datos = (array)$request->input();
        $to_number = $datos['to_number'];
        $dateSent = $datos['dateSent'];

        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        // Query parameters To string<phone-number>, From string<phone-number> DateSent string<date-time>,PageSize integer,Page integer
        $messages = $twilio->messages->read(
            [
                "to" => "+51" . $to_number,
                "from" => getenv("TWILIO_MY_NUMBER"),
                //"dateSent" => new \DateTime("2024-07-11T00:00:00Z"),
                "dateSent" => new \DateTime($dateSent . "T00:00:00Z")
            ],
            20
        );

        foreach ($messages as $record) {
            var_dump($record->body);
        }
    }
    public function listMessagesThatWereSentBeforeSpecificDate(Request $request)
    {
        $datos = (array)$request->input();
        $dateSentBefore = $datos['dateSentBefore'];

        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $messages = $twilio->messages->read(
            ["dateSentBefore" => new \DateTime($dateSentBefore . "T00:00:00Z")],
            20
        );

        foreach ($messages as $record) {
            var_dump($record->body);
        }
    }
    public function listMessagesWithinSpecificTimePeriod(Request $request)
    {
        $datos = (array)$request->input();
        $dateSentBefore = $datos['dateSentBefore'];
        $dateSentAfter = $datos['dateSentAfter'];

        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $messages = $twilio->messages->read(
            [
                "dateSentBefore" => new \DateTime($dateSentBefore . "T00:00:00Z"),
                "dateSentAfter" => new \DateTime($dateSentAfter . "T00:00:00Z")
            ],
            20
        );

        foreach ($messages as $record) {
            print $record->body;
        }
    }
}
