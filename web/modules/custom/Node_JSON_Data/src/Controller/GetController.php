<?php

namespace Drupal\Node_JSON_Data\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetController extends ControllerBase {

  public function Get($apikey, $node_id, $content_type) {
    $config = $this->config('Node_JSON_Data.settings');

    if( $config->get('api_key') !== $apikey){
      $response['message'] = 'API is invalid';
      return new JsonResponse($response);
    }
    else {
      $types = \Drupal::entityTypeManager()->getStorage('node_type')->load($content_type);
      if(empty($types)){
        $response['message'] = ' content type doesnt exist';
        return new JsonResponse($response);
      }

    else {
      $node = \Drupal::entityManager()->getStorage('node')->load($node_id);
      $type = $node->getType();

      if(empty($node)){
        $response['message'] = 'node id doesnt exist';
        return new JsonResponse($response);
      }

      else if($type != $content_type){
        $response['message'] = 'node doesnt match the content type';
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
}
