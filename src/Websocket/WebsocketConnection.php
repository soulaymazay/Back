<?php
    namespace App\Websocket;
     
    use Exception;
    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;
    use SplObjectStorage;use Psr\Log\LoggerInterface;
    use Symfony\Component\Console\Output\OutputInterface;
class WebsocketConnection
{
   /**
 * @var ConnectionInterface
 */
    public $connection;
    /**
 * @var string
 */
    public $userType;

    /**
 * @var string
 */
    public $userId;
    /**
 * @var string
 */
public $position;
    



}
    ?>