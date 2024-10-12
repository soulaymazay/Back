<?php
    namespace App\Websocket;

class WebsocketMessage
{
    function __construct(){
        $this->type = "";
        $this->clientDestination = "";
        $this->clientPosition = "";
        $this->clientDestinationGPS = "";
        $this->clientPositionGPS = "";
        $this->chauffeurPosition = "";
        $this->clientId = 0;
        $this->chauffeurId = 0;
        $this->moyen="";
        $this->courseId="";
    }
    public $type;
    public $clientDestination;
    public $clientPosition;
    public $clientDestinationGPS;
    public $clientPositionGPS;
    public  $chauffeurPosition;
    public  $courseId;
    public $moyen;
    public int $clientId;
    public int $chauffeurId;
    public function set($data) {
        foreach ($data AS $key => $value) $this->{$key} = $value;
    }
}