<?php
namespace Pili;

use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;

class Auth implements SubscriberInterface
{
    private $accessKey;
    private $secretKey;
    private $authPrefix;

    public function __construct($accessKey, $secretKey, $authPrefix = 'pili')
    {
        $this->accessKey  = $accessKey;
        $this->secretkey  = $secretKey;
        $this->authPrefix = $authPrefix;
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
        while (isset($b) && !$b->eof()) {
            $body .= $b->read(1024);
        }
        $signature = Utils::signRequest($this->accessKey, $this->secretkey, $url, $body);
        $e->getRequest()->setHeader('Authorization', $this->authPrefix . ' ' . $signature);
    }
}
