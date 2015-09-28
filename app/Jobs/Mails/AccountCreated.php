<?php namespace App\Jobs\Mails;

use App\User;
use App\Jobs\Job;
use Clockwork\Facade\Clockwork;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountCreated extends Job implements SelfHandling, ShouldQueue
{
    protected $user;


    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param  Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        Clockwork::startEvent('mail.account_created', 'Mail account created.');
        $mailer->send('mails.accountCreated', [
            'username' => $this->user->username
        ], function ($message) {
            $message->to($this->user->email, $this->user->username)->subject('Account created');
        });
        Clockwork::endEvent('mail.account_created');
    }
}