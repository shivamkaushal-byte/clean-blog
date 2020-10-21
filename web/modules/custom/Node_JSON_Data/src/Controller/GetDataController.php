<?php

namespace Drupal\Node_JSON_Data\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetDataController extends ControllerBase {

  public function GetData($apikey, $node_id) {
    $config = $this->config('Node_JSON_Data.settings');

    if( $config->get('api_key') !== $apikey){
      $response['message'] = 'API is invalid';
      return new JsonResponse($response);
    }

    else {
      $node = \Drupal::entityManager()->getStorage('node')->load($node_id);
      if(empty($node)){
        $response['message'] = 'node id doesnt exist';
        return new JsonResponse($response);
      }
      else{
        $result = [
          'title' => $node->title->value,
          'body' => $node->body->value,
        ];
      }
    }
    return new JsonResponse($result);
  }
}
