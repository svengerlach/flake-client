<?php

namespace Svengerlach\FlakeClient;

interface ClientInterface
{
    public function get($quantity = 1);
}