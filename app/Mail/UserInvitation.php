<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $password;
    public $company;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$password,$company)
    {
        $this->user = $user;
        $this->password = $password;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.user_invitation')->with(['user'=> $this->user, 'password'=> $this->password, 'company'=> $this->company])->subject(__('Regarding to User Login Invitation'));
    }
}
