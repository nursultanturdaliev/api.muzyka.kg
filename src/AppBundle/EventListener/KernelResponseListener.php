<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 12/27/16
 * Time: 3:39 PM
 */

namespace AppBundle\EventListener;


use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class KernelResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;
        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $responseHeaders->set('Access-Control-Allow-Origin', '*');
        $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
    }
}