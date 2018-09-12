<?php

namespace Drupal\update_notifier\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Update notifier entity entities.
 */
class UpdateNotifierEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
