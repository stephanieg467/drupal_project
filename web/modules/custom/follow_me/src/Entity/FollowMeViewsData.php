<?php

namespace Drupal\follow_me\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Follow me entities.
 */
class FollowMeViewsData extends EntityViewsData {

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
