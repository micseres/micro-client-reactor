<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 17.09.18
 * Time: 11:59
 */

namespace Micseres\MicroClientReactor;

use Micseres\MicroClientReactor\Exception\ServiceException;
use Micseres\MicroClientReactor\Exception\SocketException;
use Micseres\MicroClientReactor\Exception\ApproveResponseException;
use Micseres\MicroClientReactor\Exception\TimeoutException;
use Micseres\MicroClientReactor\Request\ClientRequest;
use Micseres\MicroClientReactor\Response\ApproveResponse;

/**
 * Class MicroClientReactor
 * @package Micseres\MicroClientReactor
 */
class MicroClientReactor
{
    const RESPONSE_WAIT_TIME = 0.25;

    /**
     * @var string
     */
    private $ip;
    /**
     * @var string
     */
    private $port;

    /** @var resource */
    private $socket;

    /**
     * @var float
     */
    private $wait;

    /**
     * MicroServiceReactor constructor.
     * @param string $ip
     * @param int $port
     * @param float $wait
     */
    public function __construct(string $ip, int $port, float $wait = self::RESPONSE_WAIT_TIME)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->wait = $wait;
    }

    /**
     * @param ClientRequest $request
     * @throws ServiceException
     * @throws SocketException
     * @throws ApproveResponseException
     * @throws TimeoutException
     *
     * @return string
     */
    public function sendAndReceive(ClientRequest $request): string
    {
        $this->create();
        $this->connect();

        $buffer = $this->prepareBuffer($request);

        $isSend = (bool)socket_write($this->socket, $buffer, mb_strlen($buffer));

        if (true !== $isSend) {
            throw new ServiceException("Can`t send request to service");
        }

        $approve = new ApproveResponse($approveResponse = $this->readPartialData($this->wait));

        if ($approve->getStatus() !== 'OK') {
            throw new ApproveResponseException($approve->getMessage());
        }

        $dataResponse = $this->readPartialData($this->wait);

        return $dataResponse;
    }

    /**
     * @param float $timeout
     * @return string
     * @throws TimeoutException
     */
    private function readPartialData(float $timeout) :string
    {
        $reply = '';
        $json = null;

        $start = round(microtime(true), 2);

        do {
            $end = round(microtime(true), 2)-$start;

            if ($end > $timeout) {
                throw new TimeoutException('Can`t wait server response. Life to short');
            }

            socket_recv($this->socket, $result, 1048, MSG_DONTWAIT);
            $reply .= $result;
            $json = json_decode(trim($reply), true);
        } while (null === $json);

        return $reply;
    }

    /**
     * @throws SocketException
     */
    private function create(): void
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (false === $socket) {
            throw new SocketException("Can`t create socket");
        }

        $this->socket = $socket;

    }

    /**
     * @throws SocketException
     */
    private function connect(): void
    {
        $isConnect = socket_connect($this->socket, $this->ip, $this->port);

        if (false === $isConnect) {
            throw new SocketException("Can`t connect socket");
        }
    }

    /**
     * @param string $buffer
     * @return string
     */
    private function prepareBuffer(string $buffer): string
    {
        return $buffer."\r\n";
    }
}
