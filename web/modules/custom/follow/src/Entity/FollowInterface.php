<?php

namespace Drupal\follow\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Follow entities.
 *
 * @ingroup follow
 */
interface FollowInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Follow name.
   *
   * @return string
   *   Name of the Follow.
   */
  public function getName();

  /**
   * Sets the Follow name.
   *
   * @param string $name
   *   The Follow name.
   *
   * @return \Drupal\follow\Entity\FollowInterface
   *   The called Follow entity.
   */
  public function setName($name);

  /**
   * Gets the Follow creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Follow.
   */
  public function getCreatedTime();

  /**
   * Sets the Follow creation timestamp.
   *
   * @param int $timestamp
   *   The Follow creation timestamp.
   *
   * @return \Drupal\follow\Entity\FollowInterface
   *   The called Follow entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Follow published status indicator.
   *
   * Unpublished Follow are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Follow is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Follow.
   *
   * @param bool $published
   *   TRUE to set this Follow to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\follow\Entity\FollowInterface
   *   The called Follow entity.
   */
  public function setPublished($published);

  /**
   * Gets the Follow revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Follow revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\follow\Entity\FollowInterface
   *   The called Follow entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Follow revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Follow revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\follow\Entity\FollowInterface
   *   The called Follow entity.
   */
  public function setRevisionUserId($uid);

}