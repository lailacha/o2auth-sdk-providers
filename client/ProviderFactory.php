<?php

namespace App;
use App\AbstractAuthProvider;

use App\Providers\Facebook;
use App\Providers\Discord;
use App\Providers\Twitch;
use App\Providers\Server;


class ProviderFactory{

    protected $providers = [];
    protected $configs = [];


    public function __construct($configs)
    {
        $this->providers = [
            "twitch" => Twitch::class,
            "facebook" => Facebook::class,
            "server" => Server::class,
            "discord" => Discord::class,
        ];

        $this->configs = $configs;
    }


    public function factory($name, $config): AbstractAuthProvider
    {
        $class = $this->providers[$name];
        return new $class($config["client_id"], $config["client_secret"], $config["redirect_uri"], $config["scope"], $config["params"]);
    }


    public function getProvider($name): AbstractAuthProvider
    {
        return $this->factory($name, $this->configs[$name]);
    }
}