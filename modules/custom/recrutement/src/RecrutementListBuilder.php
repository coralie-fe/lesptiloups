<?php

namespace Drupal\recrutement;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Recrutement entities.
 *
 * @ingroup recrutement
 */
class RecrutementListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;


    /**
     * {@inheritdoc}
     */
    public function getDefaultOperations(EntityInterface $entity) {
        $operations = parent::getDefaultOperations($entity);

        if ($entity->hasLinkTemplate('confirm-form')) {
            $operations['confirm'] = array(
                'title' => t('Confirm'),
                'weight' => -20,
                'url' => $entity->toUrl('confirm-form'),
            );
        }
        if ($entity->hasLinkTemplate('archive-form')) {
            $operations['archive'] = array(
                'title' => t('Archive'),
                'weight' => 21,
                'url' => $entity->toUrl('archive-form'),
            );
        }
        return $operations;
    }

  public function buildHeader() {
    $header['id'] = $this->t('Recrutement ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\recrutement\Entity\Recrutement */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.recrutement.edit_form', array(
          'recrutement' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
