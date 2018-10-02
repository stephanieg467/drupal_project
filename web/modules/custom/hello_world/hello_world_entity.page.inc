<?php

/**
 * @file
 * Contains hello_world_entity.page.inc.
 *
 * Page callback for Hello world entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Hello world entity templates.
 *
 * Default template: hello_world_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_hello_world_entity(array &$variables) {
  // Fetch HelloWorldEntity Entity Object.
  $hello_world_entity = $variables['elements']['#hello_world_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
