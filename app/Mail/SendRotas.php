<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRotas extends Mailable
{
    use Queueable, SerializesModels;

    public $rotas_data;
    public $role_datas;
    public $location_datas;
    public $id;
    public $date;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($rotas_data, $role_datas, $location_datas, $id, $date)
    {
        $this->rotas_data = $rotas_data;
        $this->role_datas = $role_datas;
        $this->location_datas = $location_datas;
        $this->id = $id;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.sendrotas')->with([
            'rotas_data'=> $this->rotas_data,
            'role_datas'=> $this->role_datas,
            'location_datas'=> $this->location_datas,
            'id'=> $this->id,
            'date'=> $this->date])->subject('Regarding to Rotas Detail');
    }
}
