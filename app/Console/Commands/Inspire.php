<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Carbon\Carbon, DatePeriod, DateInterval;
class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $saturdays = $this->test123();
        $collect = collect();
        foreach ($saturdays as $saturday)
        {
            $collect = $collect->merge([$saturday->toDateString()]);
        }
        dd($collect);
        $count = 4;
        // $this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
        $dates = collect();
        $saturday = Carbon::parse('first saturday of this month');
        while( $count )
        {
            $dates = $dates->merge([$saturday]);
            $saturday = $saturday->parse('first saturday of next month');
            $count--;
        }
        dd($dates);
    }

    public function test123()
    {
         return new DatePeriod(
            Carbon::parse("first saturday of this month"),
            DateInterval::createFromDateString( "first saturday of next month" ),
            Carbon::parse("first saturday of +4 months")
        );
    }
}
