<?php

namespace Drupal\recrutement\Controller;

use Drupal\Core\Controller\ControllerBase;


class HelloController extends ControllerBase  {
  function content() {
    $recrutement = $this->entityManager()->getStorage('recrutement')->create(array(
      'type' => 'recrutement',
    ));
    $form = \Drupal::service('entity.form_builder');
    $form = $form->getForm($recrutement, 'default');
    return $form;
  }

}