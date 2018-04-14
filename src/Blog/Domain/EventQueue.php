<?php

namespace Blog\Domain;

interface EventQueue
{
    public function registerHandler(string $eventName, callable $callable): void;
    public function pushEvent(GenericEvent $event);
}
