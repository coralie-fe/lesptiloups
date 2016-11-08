<?php

namespace Drupal\recrutement\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Recrutement entities.
 */
class RecrutementViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['recrutement']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Recrutement'),
      'help' => $this->t('The Recrutement ID.'),
    );

    return $data;
  }

}
