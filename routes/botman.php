<?php
use App\Http\Controllers\BotManController;
use Carbon\Carbon;
use GuzzleHttp\Client;

$botman = resolve('botman');

$botman->hears('GetNextConnection', function ($bot) {

    $client = new Client([
        'headers' => ['Authorization' => 'Bearer ' . env('DOBBY_ACCESS_TOKEN')],
    ]);

    $connection = json_decode(
        $client->request('GET', 'https://dobby.levity.ch/api/connection/next')->getBody()
    );

    $departure = Carbon::createFromTimestamp($connection->departure / 1000);
    $nowUntilDepartureTime = $departure->diffInSeconds(now());
    $timeToLeave = ceil(($nowUntilDepartureTime - $connection->time_to_station) / 60);

    $bot->reply("Your next connection is at {$departure->format('h:i')}. You have to leave in {$timeToLeave} Minutes.");
});
