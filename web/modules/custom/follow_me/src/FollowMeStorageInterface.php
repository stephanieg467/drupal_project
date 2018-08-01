<?php

namespace Drupal\follow_me;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\follow_me\Entity\FollowMeInterface;

/**
 * Defines the storage handler class for Follow me entities.
 *
 * This extends the base storage class, adding required special handling for
 * Follow me entities.
 *
 * @ingroup follow_me
 */
interface FollowMeStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Follow me revision IDs for a specific Follow me.
   *
   * @param \Drupal\follow_me\Entity\FollowMeInterface $entity
   *   The Follow me entity.
   *
   * @return int[]
   *   Follow me revision IDs (in ascending order).
   */
  public function revisionIds(FollowMeInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Follow me author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Follow me revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\follow_me\Entity\FollowMeInterface $entity
   *   The Follow me entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(FollowMeInterface $entity);

  /**
   * Unsets the language for all Follow me with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
