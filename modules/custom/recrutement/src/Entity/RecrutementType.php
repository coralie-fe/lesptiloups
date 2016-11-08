<?php

namespace Drupal\recrutement\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\recrutement\RecrutementTypeInterface;

/**
 * Defines the Recrutement type entity.
 *
 * @ConfigEntityType(
 *   id = "recrutement_type",
 *   label = @Translation("Recrutement type"),
 *   handlers = {
 *     "list_builder" = "Drupal\recrutement\RecrutementTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\recrutement\Form\RecrutementTypeForm",
 *       "edit" = "Drupal\recrutement\Form\RecrutementTypeForm",
 *       "delete" = "Drupal\recrutement\Form\RecrutementTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\recrutement\RecrutementTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "recrutement_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "recrutement",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/recrutement_type/{recrutement_type}",
 *     "add-form" = "/admin/structure/recrutement_type/add",
 *     "edit-form" = "/admin/structure/recrutement_type/{recrutement_type}/edit",
 *     "delete-form" = "/admin/structure/recrutement_type/{recrutement_type}/delete",
 *     "collection" = "/admin/structure/recrutement_type"
 *   }
 * )
 */
class RecrutementType extends ConfigEntityBundleBase implements RecrutementTypeInterface {
  /**
   * The Recrutement type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Recrutement type label.
   *
   * @var string
   */
  protected $label;

}
