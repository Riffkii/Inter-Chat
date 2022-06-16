<?php
require_once __DIR__ . '/vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Web\InterChat\Service\FriendshipService;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\SessionRepository;

class Websocket implements MessageComponentInterface {

    protected $clients;
    protected $users;
    protected $friendshipService;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
        $this->friendshipService = new FriendshipService(new FriendshipRepository(Database::getConnection('app')),
                                                         new SessionRepository(Database::getConnection('app')));
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        //Online Status
        // if($usernameOS = json_decode($msg)->usernameOS) {
        //     // $this->users[$from->resourceId] = $usernameOS;
        //     // $userFriends = $this->users;
        //     // $friendsRaw = $this->friendshipService->showFriendsWithoutCookie($usernameOS);
        //     // $friends = [];
        //     // foreach($friendsRaw as $friendRaw) {
        //     //     $friends[] = $friendRaw->getUsername();
        //     // }

        //     // foreach($userFriends as $key => $value) {
        //     //     if(!in_array($value, $friends, true)) {
        //     //         unset($userFriends[$key]);   
        //     //     }
        //     // }

        //     // foreach($this->clients as $client) {
        //     //     if ($from !== $client && array_key_exists()) {
        //     //         $client->send($msg);
        //     //     }
        //     // }
        // }

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Websocket()
        )
    ),
    3000
);

$server->run();