<?php

/**
 * @file
 * Contains \Drupal\content_entity_example\Form\ContactDeleteForm.
 */

namespace Drupal\recrutement\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a content_entity_example entity.
 *
 * @ingroup content_entity_example
 */
class RecrutementConfirmForm extends ContentEntityConfirmFormBase {
    /**
     * {@inheritdoc}
     */
    public function getQuestion() {
        return $this->t('Are you sure you want to confirm entity %name?', array('%name' => $this->entity->label()));
    }

    public function getDescription() {
        return $this->t('');
    }

    /**
     * {@inheritdoc}
     *
     * If the delete command is canceled, return to the contact list.
     */
    public function getCancelURL() {
        return new Url('entity.recrutement.collection');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
        return $this->t('Confirm');
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $render_array_entity = $this->entityManager->getViewBuilder('recrutement')->view($this->entity,'preview');
        $form['recrutement_render'] = array(
            '#markup' => \Drupal::service('renderer')->render($render_array_entity)
        );
        $this->entity->getStatut();
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     *
     * Delete the entity and log the event. log() replaces the watchdog.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->entity->changeRecrutementStatus(1);
        $entity = $this->getEntity();
        $entity->save();

       /* \Drupal::logger('content_entity_example')->notice('@type: deleted %title.',
            array(
                '@type' => $this->entity->bundle(),
                '%title' => $this->entity->label(),
            ));*/
        //$form_state->setRedirect('entity.recrutement.collection');
    }

}
