<?php

namespace Drupal\hello\Routing;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RouteSubscriberBase;

class HelloRouteSubscriber extends RouteSubscriberBase {

    protected function alterRoutes(RouteCollection $collection) {

        $route = $collection->get('entity.user.canonical');
        $route->setRequirements(['_access_hello' => '10']);
        
      }

}
