<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
   
    $data['annonce_user_views']['table']['group'] = t('Annonce History');
    $data['annonce_user_views']['table']['provider'] = 'annonce_module';
    $data['annonce_user_views']['table']['base'] = array(
      
      // Identifier (primary) field in this table for Views.  
      'field' => 'id',
      // Label in the UI.
      'title' => t('Histoire Annonce'),
      // Longer description in the UI. Required.
      'help' => t('Annonce history contains historical datas and can be related to annonces.'),
      'weight' => -10,
    );
    $data['annonce_user_views']['uid'] = [
      'title' => $this->t('Annonce view User Id'),
      'help' => $this->t('Annonce view User Id'),
      'field' => ['id'=> 'numeric'],
      'sort' => ['id' =>'standard'],
      'filter' => ['id' => 'numeric'],
      'argument' => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'users_field_data',
        'base_field' => 'uid',
        'id' => 'standard',
        'label' => $this->t('Annonce history UID -> User ID'),
      ],
    ];

    $data['annonce_user_views']['aid'] = [
      'title' => $this->t('Annonce content Id'),
      'help' => $this->t('Annonce content Id'),
      'field' => ['id'=> 'numeric'],
      'sort' => ['id' =>'standard'],
      'filter' => ['id' => 'numeric'],
      'argument' => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'annonce_field_data',
        'base_field' => 'id',
        'id' => 'standard',
        'label' => $this->t('Annonce history AID -> User ID')],
      ];

    $data['annonce_user_views']['time'] = [
      'title' => $this->t('Annonce time'),
      'help' => $this->t('Annonce time'),
      'field' => ['id'=> 'date'],
      'sort' => ['id' =>'date'],
      'filter' => ['id' => 'date'],
      ];

    return $data;
  }

}
