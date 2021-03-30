<?php

use Illuminate\Support\Facades\Route;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/', function () {
	// get request body and line signature header
	$body 	   = file_get_contents('php://input');
	$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

	// log body and signature
	file_put_contents('php://stderr', 'Body: '.$body);

	// is LINE_SIGNATURE exists in request header?
	if (empty($signature)){
		return $response->withStatus(400, 'Signature not set');
	}

	// // is this request comes from LINE?
	// if(env('PASS_SIGNATURE') == false && ! SignatureValidator::validateSignature($body, $_ENV['LINE_BOT_CHANNEL_SECRET'], $signature)){
	// 	return $response->withStatus(400, 'Invalid signature');
	// }

	// init bot
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);
    $fp = fopen("/var/www/html/linebot/storage/logs/event_line.log", "a");
    fwrite($fp, "body : " . $body . "\n");
	$data = json_decode($body, true);
	foreach ($data['events'] as $event)
	{

		$userMessage = $event['message']['text'];
		// if(strtolower($userMessage) == 'halo')
		// {
		// 	$message = "Halo juga";
        //     $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
		// 	$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
		// 	return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		// }
	}
	

});
