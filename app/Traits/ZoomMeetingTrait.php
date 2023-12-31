<?php
namespace App\Traits;

use GuzzleHttp\Client;
use App\Models\Utility;
use Log;

trait ZoomMeetingTrait
{
    public $client;
    public $jwt;
    public $headers;
    public $meeting_url="https://api.zoom.us/v2/";
    public function __construct()
    {
        $this->client = new Client();
    }


    private function retrieveZoomUrl()
    {
        return $this->meeting_url;
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function createmitting($data)
    {
        $settings  = Utility::settings(\Auth::user()->id);
        if((isset($settings['zoom_api_key']) && !empty($settings['zoom_api_key'])) && (isset($settings['zoom_secret_key']) && !empty($settings['zoom_secret_key']))){
            $path = 'users/me/meetings';
            $url = $this->retrieveZoomUrl();

            $body = [
                'headers' => $this->getHeader(),
                'body'    => json_encode([
                    'topic'      => $data['topic'],
                    'type'       => self::MEETING_TYPE_SCHEDULE,
                    'start_time' => $this->toZoomTimeFormat($data['start_time']),
                    'duration'   => $data['duration'],
                    'password' => $data['password'],
                    'agenda'     => (! empty($data['agenda'])) ? $data['agenda'] : null,
                    'timezone'     => 'Asia/Kolkata',
                    'settings'   => [
                        'host_video'        => ($data['host_video'] == "1") ? true : false,
                        'participant_video' => ($data['participant_video'] == "1") ? true : false,
                        'waiting_room'      => true,
                    ],
                ]),
            ];

            $response =  $this->client->post($url.$path, $body);

                return [
                    'success' => $response->getStatusCode() === 201,
                    'data'    => json_decode($response->getBody(), true),
                ];
        }
        else
        {
            return 0;
        }
    }
    public function meetingUpdate($id, $data)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([
                'topic'      => $data['topic'],
                'type'       => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration'   => $data['duration'],
                'agenda'     => (! empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone'     => config('app.timezone'),
                'settings'   => [
                    'host_video'        => ($data['host_video'] == "1") ? true : false,
                    'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    'waiting_room'      => true,
                ],
            ]),
        ];
        $response =  $this->client->patch($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
            'data'    => json_decode($response->getBody(), true),
        ];
    }
    public function get($id)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([]),
        ];

        $response =  $this->client->get($url.$path, $body);
            return [
                'success' => $response->getStatusCode() === 204,
                'data'    => json_decode($response->getBody(), true),
            ];
    }
    public function delete($id)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
        ];

        $response =  $this->client->delete($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }
    public function getHeader()
    {
        if($this->getToken() != 'false')
        {
            return [
                'Authorization' => 'Bearer'.$this->getToken(),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ];
        }
    }
    public function getToken()
    {
        $settings  = Utility::settings(\Auth::user()->id);
        if((isset($settings['zoom_api_key']) && !empty($settings['zoom_api_key'])) && (isset($settings['zoom_secret_key']) && !empty($settings['zoom_secret_key']))){
            $key = $settings['zoom_api_key'];
            $secret = $settings['zoom_secret_key'];
            $payload = [
                'iss' => $key,
                'exp' => strtotime('+1 minute'),
            ];

            return \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
        }else{
            return false;
        }
    }
}

 ?>
