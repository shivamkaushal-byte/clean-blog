<?php

namespace Drupal\node_generator\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class NodeGeneratorForm extends FormBase {

  public function getFormId(){
    return 'node_generator';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    $node_types = \Drupal\node\Entity\NodeType::loadMultiple();
    $options = [];
    foreach ($node_types as $node_type) {
      $options[$node_type->id()] = $node_type->label();
    }
    $form['content_types'] = [
      '#type' => 'select',
      '#title' => t('content types'),
      '#description' => 'choose the content type',
      '#options' => $options,

    ];

    $form['number_of_nodes'] = [
      '#type' => 'textfield',
      '#title' => t('number of nodes'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('save'),

    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $validate = $form_state->getValue('number_of_nodes');
    if ($validate < 2 || $validate > 10) {
      $form_state->setErrorByName('number_of_nodes', 'Minimum 2 & Maximimum 10 nodes to be generated.');
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state ){
    $content_type = $form_state->getValues()['content_types'];
    $node_count = $form_state->getValues()['number_of_nodes'];
    $title = $form_state->getValues()['title'];
    $body = $form_state->getValues()['body']['value'];
    $form_id = $form['form_id']['#value'];
    $batch = [
     'title' => 'nodes generating.',
     'operations' => [
     [
       '\Drupal\node_generator\NodeGenerateBatch::Generator',
       [$content_type, $node_count, $title, $body, $form_id],
       ],
     ],
     'finished' => '\Drupal\node_generator\NodeGenerateBatch::NodeGenerateCallback',
   ];
   batch_set($batch);
   }
 }
