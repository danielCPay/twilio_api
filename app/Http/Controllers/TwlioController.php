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
            "friendlyName" => "My Test Conversation",
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
            ->conversations("CHbcf3d430cb084d76a62656481cd8c61a")
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
            ->conversations("CHbcf3d430cb084d76a62656481cd8c61a")
            ->participants->create([
                "messagingBindingAddress" => "+17869392966",
                "messagingBindingProxyAddress" =>
                "+17863966813",
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
            ->conversations("CHbcf3d430cb084d76a62656481cd8c61a")
            ->participants->create(["identity" => "testConversation"]);

        print $participant->sid;
    }

    public function createConversationMessage()
    {
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $message = $twilio->conversations->v1
            ->conversations("CHbcf3d430cb084d76a62656481cd8c61a")
            ->messages->create([
                "author" => "testConversation",
                "body" => "Hello!",
            ]);

        print $message->sid;
    }
    public function listAllConversationMessages()
    {

        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $messages = $twilio->conversations->v1
            ->conversations("CHbcf3d430cb084d76a62656481cd8c61a")
            ->messages->read([], 50);

        foreach ($messages as $record) {
            var_dump("Author: " . $record->author . " BodySms: " . $record->body
                . " SID: " . $record->sid);
        }     
    }

    # SMS SEND API
    public function sendAnSms(Request $request)
    {       
        $datos = (array)$request->input();
        $recipients = $datos['recipients'];
        $body_sms = $datos['body_sms'];

        $account_sid = getenv('TWILIO_ACCOUNT_SID');
        $auth_token = getenv('TWILIO_AUTH_TOKEN');
        $twilio_number = getenv("TWILIO_MY_NUMBER");

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
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
        $array_messsages = [];

        foreach ($messages as $record) {
            # print $record->sid;
            # var_dump($record->body);
            $array_messsages[] = array("messages" => $record->body);
        }

        var_dump($array_messsages);
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
