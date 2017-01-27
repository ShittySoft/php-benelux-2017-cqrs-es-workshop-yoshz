<?php
declare(strict_types=1);
namespace Building\Domain\Command;

use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

class CheckOutBuilding extends Command
{
    private $buildingId;
    private $username;

    public function __construct(Uuid $buildingId, string $username)
    {
        $this->init();
        $this->buildingId = $buildingId;
        $this->username = $username;
    }

    public function buildingId(): Uuid
    {
        return $this->buildingId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function payload()
    {
        return [
            'buildingId' => $this->buildingId,
            'username' => $this->username
        ];
    }

    protected function setPayload(array $payload)
    {
        $this->buildingId = $payload['buildingId'];
        $this->username = $payload['username'];
    }
}