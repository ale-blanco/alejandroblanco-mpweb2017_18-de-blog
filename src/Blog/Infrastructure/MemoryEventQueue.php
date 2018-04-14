<?php

namespace Blog\Infrastructure;

use Blog\Domain\EventQueue;
use Blog\Domain\GenericEvent;

class MemoryEventQueue implements EventQueue
{
    private $handlers = [];

    public function registerHandler(string $eventName, callable $callable): void
    {
        if (!isset($this->handlers[$eventName])) {
            $this->handlers[$eventName] = [];
        }

        $this->handlers[$eventName][] = $callable;
    }

    public function pushEvent(GenericEvent $event)
    {
        $eventName = get_class($event);
        if (!isset($this->handlers[$eventName])) {
            return;
        }

        foreach ($this->handlers[$eventName] as $handler) {
            $handler($event);
        }
    }
}
