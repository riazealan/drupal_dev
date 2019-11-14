<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Console\Bootstrap\Drupal;
use \Drupal\user\UserInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;


class HelloController extends ControllerBase{

    public function content()
    {
        
        $msg = $this->t('you are name @username', ['@username'=> $this->currentUser()->getDisplayName(),]);
        return ['#markup' => $msg];
    
    }

    public function nodelist($nodetype = NULL)
    {
    // afichage des type de contenu
    $node_types =  \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
    
    $node_type_items = [];
    foreach ($node_types as $node_type){
        $url = new Url('hello.hello2', ['nodetype' => $node_type->id()]);
        $node_type_link = new Link($node_type->label(), $url);
        $node_type_items[] = $node_type_link;
    }   
    $node_type_list = [
        '#theme' => 'item_list',
        '#items' => $node_type_items,
        '#title' => $this->t('Filter by node type'),
    ];

    $node_storage =  \Drupal::entityTypeManager()->getStorage('node');
    $query = $node_storage->getQuery();
    if($nodetype){
        $query->condition('type', $nodetype);
    }
    $nids = $query->pager()->execute();
    $nodes = $node_storage->loadMultiple($nids);
    $items = [];
    foreach ($nodes as $node) {
        $items[] = $node->toLink();
    } 
    
    $list = ['#theme' => 'item_list',
        '#items' => $items,
        ];
    $pager = ['#type' => 'pager'];
    return ['node_type_list' => $node_type_list, 
            'pager-top' => $pager, 
            'list' => $list, 
            'pager-bottom' => $pager,
            '#cache' => ['max-age' => '0'],
            ];

}
/**
 * @Param \Drupal\user\UserInterface $user
 */
public function connection(\Drupal\user\UserInterface $user)
{

    $query = \Drupal::database()->select('hello_user_statistics', 'h');
    $query->fields('h', ['action', 'time'])->condition('uid', $user->id());
    $results = $query->execute();
    $user_statistics = [];
    $connexions = 0;
    foreach($results as $record){
        $user_statistics[]= [
            $record->action =='1' ? $this->t('Login'): $this->t('Logout'),
            \Drupal::service('date.formatter')->format($record->time),];
         $connexions += $record->action;   
    
    }

    $user = $this->currentUser()->getDisplayName();
    
    $table = [
        '#type' => 'table',
        '#header' => ['Action', 'Time'],
        '#rows' => $user_statistics,
        '#empty'=> $this->t('No connection yet'),

    ];
    $output = array(
        '#theme' => 'hello_module',
        '#data' => 'the user'.' ' .$user. ' '. 'has been connected'.' ' .$connexions.' '. 'time(s)',
    );
    
    return [$output, $table];
}




}