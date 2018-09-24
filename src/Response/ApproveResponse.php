<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 17.09.18
 * Time: 18:56
 */

namespace Micseres\MicroClientReactor\Response;

use Micseres\MicroClientReactor\Exception\ApproveResponseException;

/**
 * Class ApproveResponse
 * @package Micseres\MicroClientReactor\Response
 */
class ApproveResponse
{
    /** @var string */
    private $status;

    /** @var string */
    private $message;

    /** @var string */
    private $taskId;

    /**
     * ApproveResponse constructor.
     *
     * @param string $data
     * @throws ApproveResponseException
     */
    public function __construct(string $data)
    {
        $data = json_decode(trim($data), true);

        if (null === $data) {
            throw  new ApproveResponseException("Invalid request format");
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
    }

    /**
     * @param string $taskId
     */
    public function setTaskId(string $taskId): void
    {
        $this->taskId = $taskId;
    }
}