<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\State\StateInterface;




class HelloForm extends FormBase{

    public function getFormId(){
        return 'HelloForm';
    }   
    /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

   if(isset($form_state->getRebuildInfo()['result'])){
    $form['description'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Result:').$form_state->getRebuildInfo()['result'],
    ];
    }   
    $form['First_Value'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First Value'),
        '#description' => $this->t('Enter the first value.'),
        '#ajax' => array(
            'callback' => array($this, 'validateTextAjax'),
            'event' => 'change', ),
         '#suffix' => '<span class="text-message-First_Value"></span>',  
        '#required' => TRUE,
        ];

    $form['Operation'] = array(
      '#type' => 'radios',
      '#options' => array(
            0 => $this->t('Ajouter'),
            1 => $this->t('Soustract'),
            2 => $this ->t('Multiply'),
            3 => $this->t('Divide'),  
            ),
          '#description' => $this->t('Choose operation for processing'),
           '#default' => '0', 
    );
    $form['Second_Value'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Second Value'),
        '#description' => $this->t('Enter the Second value.'),
        '#ajax' => array(
            'callback' => array($this, 'validateTextAjax'),
            'event' => 'change', ),
         '#suffix' => '<span class="text-message-Second_Value"></span>',
        '#required' => TRUE,
      ];


    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Calcuate'),
      
    ];

    return $form;

  }

  public function validateForm(array &$form, FormStateInterface $form_state){

    $value_1 = $form_state->getValue('First_Value'); 
    if(!is_numeric($value_1)){
        $form_state->setErrorByName('First_Value', $this->t('Value 1 Must be numeric!'));
    }

    $value_2 = $form_state->getValue('Second_Value'); 
    if(!is_numeric($value_2) && !empty($value_2)){
        $form_state->setErrorByName('Second_Value', $this->t('Value 2 Must be numeric!'));
    }

  }
  public function validateTextAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $field = $form_state->getTriggeringElement()['#name'];
    if (is_numeric($form_state->getValue(str_replace('-', '_', $field)))){
      $css = ['border' => '2px solid green'];
      $message = $this->t('OK!');   
    } else {
      $css = ['border' => '2px solid red'];
      $message = $this->t('%field must be numeric!', ['%field' => $form[$field]['#title']]);
    }
 
    $response->AddCommand(new CssCommand("[name=$field]", $css));
    $response->AddCommand(new HtmlCommand('.text-message-'. $field, $message));
    
    return $response;

  }
  public function submitForm(array &$form, FormStateInterface $form_state)
  {   
    //   $this->messenger->addMessage('value1: '.$form_state->getValue('First_Value'));
    //   $this->messenger->addMessage('value2: '.$form_state->getValue('Second_Value'));  
     
      $value_1 = $form_state->getValue('First_Value');
      $value_2 = $form_state->getValue('Second_Value'); 
      $i = $form_state->getValue('Operation');
      switch ($i) {
        case 0:
         $result =  $value_1 + $value_2;
        break;
        case 1:
         $result =  $value_1 - $value_2;
        break;
        case 2:
         $result =  $value_1 * $value_2;
        break;
        case 3:
        if($value_2 != 0){
         $result =  $value_1 / $value_2;}
        else {$result = 'Not divied by 0, pls change the value';}
        break;
       
        }
    
        // $this->messenger()->addMessage($result);
        $form_state->addRebuildInfo('result', $result);
        // $form_state->setRedirect('hello.hello');
        $form_state->setRebuild();
        \Drupal::state()->set('hello_form_submission_time', REQUEST_TIME);


   }

}
