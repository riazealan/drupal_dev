<?php

namespace Drupal\hello\Plugin\Block;
use Drupal\Core\Block\BlockBase; 

/**
 * Provides a session block. 
 * @Block(
 *  id = "Session_block",
 *  admin_label = @Translation("session_block!")
 * ) 
 */
class SessionBlock extends BlockBase {
    /**
     * Implements Drupal\Core\Block\BlockBase::build().
     */  
    Public function build() { 
    $database = \Drupal::database();
    $session_num = $database->select('sessions', 's')->countQuery()->execute()->fetchField();
    return [
        '#markup' => $this->t('welcome %num', ['%num' => $session_num ]),
        '#cache' => [
            // 'key' => ['hello_block'],
            // 'contexts' => ['user'],
            'max-age' => '0' 
        ],
    ];
  }
}
