<?php

namespace Drupal\node_generator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class Feedback extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'feedback_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $uid= $user->get('uid')->value;

    $form['name'] = [
      '#title' => 'Name',
      '#type' => 'textfield',
      '#placeholder' => 'Enter Name'
    ];

    $options = [
      'value_1' => '1',
      'value_2' => '2',
      'value_3' => '3',
      'value_4' => '4',
      'value_5' => '5'
    ];

    $form['feedback'] = [
      '#type' => 'radios',
      '#title' => 'Feedback',
      '#options' => $options,
      '#description' => 'Select one of the option.',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
      '#button_type' => 'primary',
    ];


    return $form;
  }

  /**
  * {@inheritdoc}
  */
 public function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValues()['name'];
    $feedback = $form_state->getValues()['feedback'];
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $uid= $user->get('uid')->value;
    $field_arr = [
    'uid' => $uid,
    'name' => $name,
    'feedback' => $feedback,
    ];
    $query = \Drupal::database();
    $query->insert('feedback')
        ->fields($field_arr)
        ->execute();
    \Drupal::messenger()->addMessage("Feedback submitted");
 }
}
