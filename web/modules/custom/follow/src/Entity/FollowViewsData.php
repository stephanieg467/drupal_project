<?php

namespace Drupal\follow\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Follow entities.
 */
class FollowViewsData extends EntityViewsData {

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
