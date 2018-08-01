<?php

namespace Drupal\follow_me\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Follow me entities.
 *
 * @ingroup follow_me
 */
interface FollowMeInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Follow me name.
   *
   * @return string
   *   Name of the Follow me.
   */
  public function getName();

  /**
   * Sets the Follow me name.
   *
   * @param string $name
   *   The Follow me name.
   *
   * @return \Drupal\follow_me\Entity\FollowMeInterface
   *   The called Follow me entity.
   */
  public function setName($name);

  /**
   * Gets the Follow me creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Follow me.
   */
  public function getCreatedTime();

  /**
   * Sets the Follow me creation timestamp.
   *
   * @param int $timestamp
   *   The Follow me creation timestamp.
   *
   * @return \Drupal\follow_me\Entity\FollowMeInterface
   *   The called Follow me entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Follow me published status indicator.
   *
   * Unpublished Follow me are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Follow me is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Follow me.
   *
   * @param bool $published
   *   TRUE to set this Follow me to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\follow_me\Entity\FollowMeInterface
   *   The called Follow me entity.
   */
  public function setPublished($published);

  /**
   * Gets the Follow me revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Follow me revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\follow_me\Entity\FollowMeInterface
   *   The called Follow me entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Follow me revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Follow me revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\follow_me\Entity\FollowMeInterface
   *   The called Follow me entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Gets the Follow me entity type.
   *
   * @return string
   *   Type of entity followed.
   */
  public function getFollowedEntityType();

  /**
   * Sets the Follow me entity type.
   *
   * @param string $followed_entity_type
   *   The Follow me type of entity followed.
   *
   * @return \Drupal\follow_me\Entity\FollowMeInterface
   *   The called Follow me entity.
   */
  public function setFollowedEntityType($followed_entity_type);

  /**
   * Gets the Follow me Entity ID.
   *
   * @return int
   *   The entity id for the follow me entity.
   */
  public function getEntityID();

  /**
   * Sets the Follow me Entity ID.
   *
   * @param int $eid
   *   The entity ID of the Follow Me entity.
   *
   * @return \Drupal\follow_me\Entity\FollowMeInterface
   *   The called Follow me entity.
   */
  public function setEntityID($eid);

}
