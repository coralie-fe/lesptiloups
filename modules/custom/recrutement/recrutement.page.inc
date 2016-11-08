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

/**
* Prepares variables for a custom entity type creation list templates.
*
* Default template: recrutement-content-add-list.html.twig.
*
* @param array $variables
*   An associative array containing:
*   - content: An array of recrutement-types.
*
* @see block_content_add_page()
*/
function template_preprocess_recrutement_content_add_list(&$variables) {
  $variables['types'] = array();
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = array(
      'link' => Link::fromTextAndUrl($type->label(), new Url('entity.recrutement.add_form', array(
        'recrutement_type' => $type->id()
      ), array('query' => $query))),
      'description' => array(
      '#markup' => $type->label(),
      ),
      'title' => $type->label(),
      'localized_options' => array(
      'query' => $query,
      ),
    );
  }
}
