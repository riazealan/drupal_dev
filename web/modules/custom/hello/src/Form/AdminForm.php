<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AdminForm extends ConfigFormBase{

public function getFormId(){
    return 'admin_form';
}   

protected function getEditableConfigNames(){
    return ['hello.settings'];

}
public function buildForm(array $form, FormStateInterface $form_state) {
    $purge_days_number = $this->config('hello.settings')->get('purge_days_number');

    $form['purge_days_number'] =   [
        '#type' => 'select',
        '#title' => $this->t('How long to keep user activity statics'),
        '#options' => [
            '0' => $this->t('Never purge'),
            '1' => $this->t('One day'),
            '2' => $this->t('Two days'),
            '7' => $this->t('One week'),
            '14' => $this->t('Two weeks'),
            '30' => $this->t('One Month'),
            ],
            '#default_value' => $purge_days_number,
            ];

        $form['actions'] = [
        '#type' => 'actions',
        ];
    
        // Add a submit button that handles the submission of the form.
        $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save Configuration'),
        
        ];
        return parent::buildForm($form, $form_state);

}
public function submitForm(array &$form, FormStateInterface $form_state)
{  
    $this->config('hello.settings')->set('purge_days_number', $form_state->getValue('purge_days_number'))->save();
    parent::submitForm($form, $form_state);
}

}

