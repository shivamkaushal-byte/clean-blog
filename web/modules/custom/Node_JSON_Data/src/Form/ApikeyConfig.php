<?php

namespace Drupal\Node_JSON_Data\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ApikeyConfig extends ConfigFormBase {

  public function getFormId(){
    return 'config_api_key_admin_settings';

  }

  protected function getEditableConfigNames(){
    return [
      'Node_JSON_Data.settings',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('Node_JSON_Data.settings');
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => 'enter the api key ',
      '#default_value' => $config->get('api_key'),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state){
    $length = strlen($form_state->getValue('api_key'));
    if($length < 16) {
      $form_state->setErrorByName('api_key', 'API key must be of 16 digit.');
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state){
    $this->config('Node_JSON_Data.settings')
    ->set('api_key' , $form_state->getValue('api_key'))
    ->save();
    parent::submitForm($form, $form_state);
  }

}
