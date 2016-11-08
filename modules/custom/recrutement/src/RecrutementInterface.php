<?php

namespace Drupal\recrutement;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Recrutement entities.
 *
 * @ingroup recrutement
 */
interface RecrutementInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Recrutement name.
   *
   * @return string
   *   Name of the Recrutement.
   */
  public function getName();

  /**
   * Sets the Recrutement name.
   *
   * @param string $name
   *   The Recrutement name.
   *
   * @return \Drupal\recrutement\RecrutementInterface
   *   The called Recrutement entity.
   */
  public function setName($name);

  /**
   * Gets the Recrutement creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Recrutement.
   */
  public function getCreatedTime();

  /**
   * Sets the Recrutement creation timestamp.
   *
   * @param int $timestamp
   *   The Recrutement creation timestamp.
   *
   * @return \Drupal\recrutement\RecrutementInterface
   *   The called Recrutement entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Recrutement published status indicator.
   *
   * Unpublished Recrutement are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Recrutement is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Recrutement.
   *
   * @param bool $published
   *   TRUE to set this Recrutement to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\recrutement\RecrutementInterface
   *   The called Recrutement entity.
   */
  public function setPublished($published);

}
