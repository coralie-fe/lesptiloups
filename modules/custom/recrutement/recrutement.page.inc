<?php

/**
 * @file
 * Contains recrutement.page.inc.
 *
 * Page callback for Recrutement entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Recrutement templates.
 *
 * Default template: recrutement.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_recrutement(array &$variables) {
  // Fetch Recrutement Entity Object.
  $recrutement = $variables['elements']['#recrutement'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
