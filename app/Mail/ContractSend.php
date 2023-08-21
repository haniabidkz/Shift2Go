<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractSend extends Mailable
{
    use Queueable, SerializesModels;

    public $estArr;
    public $contract;
    public $employee;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($estArr,$contract,$employee)
    {
        $this->estArr = $estArr;
        $this->contract = $contract;
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        // dd($this->estArr,$this->contract);
        return $this->markdown('email.contract_send')->subject('Contract Regarding - '.env('APP_NAME'))->with(
            [
               'details' => $this-> estArr ,
               'contract' =>  $this-> contract ,
               'employee' =>  $this-> employee ,

                // 'email' =>  $this->estArr,
                // 'contract_subject' => $this->subject,
                // 'contract_client' => $this->name,
                // 'contract_start_date' => $this->start_date,
                // 'contract_end_date' =>$this->end_date ,
            ]
        );
    }
}
