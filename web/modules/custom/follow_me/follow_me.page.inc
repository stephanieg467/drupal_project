<?php

/**
 * @file
 * Contains follow_me.page.inc.
 *
 * Page callback for Follow me entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Follow me templates.
 *
 * Default template: follow_me.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_follow_me(array &$variables) {
  // Fetch FollowMe Entity Object.
  $follow_me = $variables['elements']['#follow_me'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
