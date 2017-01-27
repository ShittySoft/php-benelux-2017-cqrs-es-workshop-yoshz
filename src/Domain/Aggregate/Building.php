<?php

declare(strict_types=1);

namespace Building\Domain\Aggregate;

use Building\Domain\DomainEvent\BuildingWasCheckedIn;
use Building\Domain\DomainEvent\BuildingWasCheckedOut;
use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Prooph\EventSourcing\AggregateRoot;
use Rhumsaa\Uuid\Uuid;

final class Building extends AggregateRoot
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $users = [];

    public static function new(string $name) : self
    {
        $self = new self();

        $self->recordThat(NewBuildingWasRegistered::occur(
            (string) Uuid::uuid4(),
            [
                'name' => $name
            ]
        ));

        return $self;
    }

    public function checkInUser(string $username)
    {
        $this->recordThat(BuildingWasCheckedIn::occur(
            (string) $this->uuid,
            [
                'username' => $username
            ]
        ));
    }

    public function checkOutUser(string $username)
    {
        $this->recordThat(BuildingWasCheckedOut::occur(
            (string) $this->uuid,
            [
                'username' => $username
            ]
        ));
    }

    public function whenNewBuildingWasRegistered(NewBuildingWasRegistered $event)
    {
        $this->uuid = $event->uuid();
        $this->name = $event->name();
    }

    public function whenBuildingWasCheckedIn(BuildingWasCheckedIn $event)
    {
        if (!in_array($event->username(), $this->users)) {
            $this->users[] = $event->username();
        }
    }

    public function whenBuildingWasCheckedOut(BuildingWasCheckedOut $event)
    {
        if (($key = array_search($event->username(), $this->users)) !== false) {
            unset($this->users[$key]);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function aggregateId() : string
    {
        return (string) $this->uuid;
    }

    /**
     * {@inheritDoc}
     */
    public function id() : string
    {
        return $this->aggregateId();
    }

    /**
     * @return array
     */
    public function users(): array
    {
        return $this->users;
    }
}
