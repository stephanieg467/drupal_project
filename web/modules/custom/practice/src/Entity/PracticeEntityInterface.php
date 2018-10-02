<?php

namespace Drupal\practice\Entity;

use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Practice entity entities.
 *
 * @ingroup practice
 */
interface PracticeEntityInterface extends EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Practice entity name.
   *
   * @return string
   *   Name of the Practice entity.
   */
  public function getName();

  /**
   * Sets the Practice entity name.
   *
   * @param string $name
   *   The Practice entity name.
   *
   * @return \Drupal\practice\Entity\PracticeEntityInterface
   *   The called Practice entity entity.
   */
  public function setName($name);

  /**
   * Gets the Practice entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Practice entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Practice entity creation timestamp.
   *
   * @param int $timestamp
   *   The Practice entity creation timestamp.
   *
   * @return \Drupal\practice\Entity\PracticeEntityInterface
   *   The called Practical entity entity.
   */
  public function setCreatedTime($timestamp);
}
