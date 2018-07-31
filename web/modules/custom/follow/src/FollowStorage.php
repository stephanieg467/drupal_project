<?php

namespace Drupal\follow;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class FollowStorage extends SqlContentEntityStorage implements FollowStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(FollowInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {follow_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {follow_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(FollowInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {follow_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('follow_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
