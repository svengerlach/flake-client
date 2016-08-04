<?php

namespace Svengerlach\FlakeClient;

class Client implements ClientInterface
{

    /**
     * @var
     */
    private $host = '127.0.0.1';

    /**
     * @var int
     */
    private $port = 9944;

    /**
     * Client constructor.
     * @param $host
     * @param $port
     */
    public function __construct($host = '127.0.0.1', $port = 9944)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function get($quantity = 1)
    {
        $socket = fsockopen($this->host, $this->port);
        
        if ( ! $socket ) {
            throw new \RuntimeException(sprintf('Could not open socket: %s:%d', $this->host, $this->port));
        }
        
        fwrite($socket, sprintf('GET %d', $quantity));
        
        $i = 0;
        
        while ( ! feof($socket) ) {
            $id = trim(fgets($socket, 64));
            
            if ( ctype_digit($id) ) {
                $i++;
                yield (int) $id;
            }
        }
        
        if ( $i != $quantity ) {
            throw new \RuntimeException(sprintf('Did not receive %d identifiers, got %d', $quantity, $i));
        }
    }

}