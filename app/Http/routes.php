<?php
use Illuminate\Http\Request;

Route::macro('after', function ($callback) {
    $this->events->listen('router.filter:after:newrelic-patch', $callback);
});


Route::group(['middleware' => ['web']], function () {
    Route::get('/sethook',function(Request $request)
    {
        $baseUrl = str_replace('sethook', '' , $request->url());
        $url = $baseUrl. 'botrock';
        $url = str_replace('http', 'https', $url);
        return Telebot::setWebhook(['url' => $url] );
    });
    Route::post('botrock',function(Request $request){
        Log::info('rabina',[$request->all()]);

        $message = $request->message;
        $firstName = $message['from']['first_name'];
        Telebot::sendMessage([
          'chat_id'                  => $message['from']['id'],
          'text'                     => 'Hi, '. $firstName.'. What Can I do for you?',
        ]);
    });
    Route::get('/', function () {
      return 'Laravel 5';
    });


    Route::get('/download', function() {

    });
});