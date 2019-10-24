<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\Console\Bootstrap\Drupal;


class HelloController extends ControllerBase{

    public function content(){
        
        $msg = $this->t('you are name @username', ['@username'=> $this->currentUser()->getDisplayName(),]);
        return ['#markup' => $msg];
       
        /*return new static(
            $container->get('current_user')
          );*/
}}