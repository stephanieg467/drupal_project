<?php

namespace Drupal\follow;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\follow\Entity\FollowInterface;

/**
 * Defines the storage handler class for Follow entities.
 *
 * This extends the base storage class, adding required special handling for
 * Follow entities.
 *
 * @ingroup follow
 */
interface FollowStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Follow revision IDs for a specific Follow.
   *
   * @param \Drupal\follow\Entity\FollowInterface $entity
   *   The Follow entity.
   *
   * @return int[]
   *   Follow revision IDs (in ascending order).
   */
  public function revisionIds(FollowInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Follow author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Follow revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\follow\Entity\FollowInterface $entity
   *   The Follow entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(FollowInterface $entity);

  /**
   * Unsets the language for all Follow with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
