<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Queue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.debug')){
            DB::listen(function ($query) {
                info($query->sql);
            });
        }

        View::addExtension('html', 'blade');

        Queue::failing(function (JobFailed $event) {
            set_time_limit(0);
            info("队列报警:{$event->connectionName} | ". $event->job->getQueue() .' | '. $event->exception->getMessage());

            $phpEmails = explode(',', env('MAIL_MONITOR_ALERT_USER'));
            $data['job'] = $event->job->getRawBody();
            $data['error'] = $event->exception->getFile() . ' LINE:' . $event->exception->getLine() . ' ERROR:'.$event->exception->getMessage();
            $data['trace'] = $event->exception->getTraceAsString();
            $data['time'] = date('Y-m-d H:i:s', time());
            Mail::send('mailtemp.queueError', $data, function ($m) use ($phpEmails, $event) {
                $m->to($phpEmails, 'alert')->subject(getenv('APP_ENV') . "=>队列报警:{$event->connectionName}");
            });
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
