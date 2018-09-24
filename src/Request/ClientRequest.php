<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 17.09.18
 * Time: 18:39
 */

namespace Micseres\MicroClientReactor\Request;

/**
 * Class Request
 * @package Micseres\MicroClientReactor
 */
class ClientRequest implements \JsonSerializable
{
    /** @var string */
    private $route;

    /** @var array */
    private $payload = [];

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addPayload(string $key, string $value): void
    {
        $this->payload[$key] = $value;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'route' => $this->getRoute(),
            'payload' => $this->getPayload(),
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }
}