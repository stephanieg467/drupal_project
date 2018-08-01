<?php

namespace Drupal\follow_me;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class FollowMeStorage extends SqlContentEntityStorage implements FollowMeStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(FollowMeInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {follow_me_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {follow_me_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(FollowMeInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {follow_me_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('follow_me_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
