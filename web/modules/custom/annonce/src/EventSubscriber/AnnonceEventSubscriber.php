<?php

namespace Drupal\annonce\EventSubscriber;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;

class AnnonceEventSubscriber implements EventSubscriberInterface {

    
    protected $currentUser;
    protected $currentRouteMatch;
    protected $current_time;
    protected $database;

    public function __construct(AccountProxyInterface $current_user, 
                                CurrentRouteMatch $current_route_match, 
                                Connection $db, 
                                TimeInterface $time)
    {
        $this->currentUser = $current_user;
        $this->currentRouteMatch = $current_route_match;
        $this->current_time = $time;
        $this->database = $db;

    }
    
    static function getSubscribedEvents(){
        $events[kernelEvents::REQUEST][] = ['onRequest'];
        return $events;
    }

    public function onRequest(){
        
        if($this->currentRouteMatch->getRouteName() == 'entity.annonce.canonical'){
           
            $annonce = $this->currentRouteMatch->getParameter('annonce');
            $this->database->insert('annonce_user_views')->fields([

                'uid' => $this->currentUser->id(),
                'time'=> $this->current_time->getRequestTime(),
                'aid'=>$annonce->id(),
            ])->execute();    
        }
    }
}