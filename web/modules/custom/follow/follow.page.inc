<?php

/**
 * @file
 * Contains follow.page.inc.
 *
 * Page callback for Follow entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Follow templates.
 *
 * Default template: follow.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_follow(array &$variables) {
  // Fetch Follow Entity Object.
  $follow = $variables['elements']['#follow'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
