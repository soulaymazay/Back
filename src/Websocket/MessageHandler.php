<?php

namespace App\Websocket;

use Exception;
use App\Websocket\WebsocketConnection;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MessageHandler implements MessageComponentInterface
{
    /**
     * @var WebsocketConnection[]
     *  */
    private $WebsocketConnections = array();
     /**
     * @var Serializer 
     *  */
   private $serializer;
    private OutputInterface $consolelogger;

    public function __construct(
        OutputInterface $OutputInterface,
    ) {
        $this->consolelogger = $OutputInterface;
        
$encoders = [new XmlEncoder(), new JsonEncoder()];
$normalizers = [new ObjectNormalizer()];

$this->serializer = new Serializer($normalizers, $encoders);
    }
    public function onOpen(ConnectionInterface $conn)
    {
        $this->consolelogger->writeln("connected");
        $querydata = explode("=", $conn->httpRequest->getUri()->getQuery());
        $socketconnection = new WebsocketConnection();
        $socketconnection->connection = $conn;
        $socketconnection->userType = $querydata[0];
        $socketconnection->userId = $querydata[1];

        array_push($this->WebsocketConnections, $socketconnection);
        $this->consolelogger->writeln($socketconnection->userId);
        $this->consolelogger->writeln($socketconnection->userType);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {    
   $data=$this->serializer->deserialize($msg,WebsocketMessage::class,'json');
   $this->consolelogger->writeln("deserialization success");

        if ($data->type == "ClientRequest") {

            foreach ($this->WebsocketConnections  as $element) {
                if ($data->chauffeurId == $element->userId) {
                    $element->connection->send($msg);

                }
            }
        }
         else if ($data->type == "ClientRequestRefused" || $data->type == "ClientRequestAccepted") {
            
            foreach ($this->WebsocketConnections  as $element) {
                $this->consolelogger->writeln("element:");

                $this->consolelogger->writeln($element->userType);
                $this->consolelogger->writeln($element->userId);

                if ($data->clientId == $element->userId) {
                    $this->consolelogger->writeln("found");

                    $element->connection->send($msg);

                }
            }
        }
        else if ($data->type == "GetActiveChauffeurs") {
            $listeChauffeurs=array();
            foreach ($this->WebsocketConnections  as $element) {

                if ("Chauffeur"==$element->userType) {
                     $message=new WebsocketMessage;

                     $message->type='ChauffeurData';

                     $message->chauffeurId=$element->userId;
                     $message->chauffeurPosition=$element->position;

                     array_push($listeChauffeurs,$message);

                }
            }
            $response=json_encode($listeChauffeurs);
            $this->consolelogger->writeln(var_dump($response));

            $from->send($response);
        }
        else if ($data->type == "UpdateChauffeurPosition") {
            foreach ($this->WebsocketConnections  as $element) {

                if ($data->chauffeurId == $element->userId) {
                    $this->consolelogger->writeln("found chauffeur to update position");
                     $element->position=$data->chauffeurPosition;
                }
            }
            $from->send("updated");
        }
        else if ($data->type == "UpdateClientPosition") {
            foreach ($this->WebsocketConnections  as $element) {

                if ($data->clientId == $element->userId) {
                    $this->consolelogger->writeln("found client to update position");
                     $element->position=$data->chauffeurPositionGPS;
                }
            }
            $from->send("updated");
        }
        else if ($data->type == "GetClientPosition") {
            foreach ($this->WebsocketConnections  as $element) {

                if ($data->clientId == $element->userId) {
                     $from->send($element->position);

                }
            }
        }
        else if ($data->type == "GetChauffeurPosition") {
            foreach ($this->WebsocketConnections  as $element) {

                if ($data->chauffeurId == $element->userId) {
                     $from->send($element->position);

                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        foreach ($this->WebsocketConnections as $key => $value) {
            if ($value->connection == $conn) {
                unset($arr[$key]);
            }
        }
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    { {
            foreach ($this->WebsocketConnections as $key => $value) {
                if ($value->connection == $conn) {
                    unset($this->WebsocketConnections[$key]);
                }
            }
        }
    }
}
