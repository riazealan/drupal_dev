<?php

namespace Drupal\hello\Routing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RouteSubscriberBase;



class HelloRouteSubscriber implements EventSubscriberInterface{

    public static function getSubscribedEvents(){

    }
    protected function alterRoutes(RouteCollection $collection) {

        $route = $collection->get('entity.user.canonical');
        $route->setRequirement([' _access_hello' => '10']);
        
      }

}