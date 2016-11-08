<?php

namespace Drupal\recrutement;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Recrutement entity.
 *
 * @see \Drupal\recrutement\Entity\Recrutement.
 */
class RecrutementAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\recrutement\RecrutementInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished recrutement entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published recrutement entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit recrutement entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete recrutement entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add recrutement entities');
  }

}
