<?php
namespace TaskLoader\Core;

$this->addService('config', ConfigProvider::class);
$this->addService('request', RequestProvider::class);
$this->addService('router', RouteContainer::class);
