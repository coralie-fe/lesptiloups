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
        $operations = array();
        $operations = parent::getDefaultOperations($entity);
        if ($entity->access('manage recrutement status') && $entity->hasLinkTemplate('confirm-form') && $entity->getStatut()!=1) {
            $operations['confirm'] = array(
                'title' => t('Confirmer'),
                'weight' => -20,
                'url' => $entity->toUrl('confirm-form'),
            );
        }
        if ($entity->access('manage recrutement status') && $entity->hasLinkTemplate('archive-form')) {
            $operations['archive'] = array(
                'title' => t('Archiver'),
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
