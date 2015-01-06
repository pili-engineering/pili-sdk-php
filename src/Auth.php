<?php
namespace Pili;

use \GuzzleHttp\Event\SubscriberInterface;
use \GuzzleHttp\Event\BeforeEvent;
use \GuzzleHttp\Event\RequestEvents;

class Auth implements SubscriberInterface
{
    private $accessKey;
    private $secretkey;
    private $authPrefix;

    public function __construct($access_key, $secret_key, $auth_prefix = 'pili')
    {
        $this->accessKey  = $access_key;
        $this->secretkey  = $secret_key;
        $this->authPrefix = $auth_prefix;
    }

    public function getEvents()
    {
        return ['before' => ['sign', RequestEvents::SIGN_REQUEST]];
    }

    public function sign(BeforeEvent $e)
    {
        $url = $e->getRequest()->getUrl();
        $b = $e->getRequest()->getBody();
        $body = '';
        while (!$b->eof()) {
            $body .= $b->read(1024);
        }
        $signature = Utils::signRequest($this->accessKey, $this->secretkey, $url, $body);
        $e->getRequest()->setHeader('Authorization', $this->authPrefix . ' ' . $signature);
    }
}

