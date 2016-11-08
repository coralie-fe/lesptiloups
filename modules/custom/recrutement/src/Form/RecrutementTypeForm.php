<?php

namespace Drupal\recrutement\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RecrutementTypeForm.
 *
 * @package Drupal\recrutement\Form
 */
class RecrutementTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $recrutement_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $recrutement_type->label(),
      '#description' => $this->t("Label for the Recrutement type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $recrutement_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\recrutement\Entity\RecrutementType::load',
      ),
      '#disabled' => !$recrutement_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $recrutement_type = $this->entity;
    $status = $recrutement_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Recrutement type.', [
          '%label' => $recrutement_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Recrutement type.', [
          '%label' => $recrutement_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($recrutement_type->urlInfo('collection'));
  }

}
