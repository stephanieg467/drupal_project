<?php

/**
 * @file
 * Contains update_notifier_entity.page.inc.
 *
 * Page callback for Update notifier entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Update notifier entity templates.
 *
 * Default template: update_notifier_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_update_notifier_entity(array &$variables) {
  // Fetch UpdateNotifierEntity Entity Object.
  $update_notifier_entity = $variables['elements']['#update_notifier_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
