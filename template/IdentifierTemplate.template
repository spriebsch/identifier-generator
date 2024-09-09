<?php

declare(strict_types=1);

namespace %%%namespace%%%;

use spriebsch\eventstore\CorrelationId;
use spriebsch\uuid\UUID;

final class %%%class%%% implements CorrelationId
{
    private UUID $uuid;

    public static function generate(): self
    {
        return new self(UUID::generate());
    }

    public static function from(string $uuid): self
    {
        return new self(UUID::from($uuid));
    }

    public static function fromUUID(UUID $uuid): self
    {
        return new self($uuid);
    }

    private function __construct(UUID $uuid)
    {
        $this->uuid = $uuid;
    }

    public function asUUID(): UUID
    {
        return $this->uuid;
    }

    public function asString(): string
    {
        return $this->uuid->asString();
    }
}
