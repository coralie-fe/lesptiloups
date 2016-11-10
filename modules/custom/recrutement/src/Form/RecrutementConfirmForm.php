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
/*    public function buildForm(array $form, FormStateInterface $form_state) {
        //get l operation et zou
    }*/

    /**
     * {@inheritdoc}
     *
     * Delete the entity and log the event. log() replaces the watchdog.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $entity = $this->getEntity();
        //$entity->delete();

       /* \Drupal::logger('content_entity_example')->notice('@type: deleted %title.',
            array(
                '@type' => $this->entity->bundle(),
                '%title' => $this->entity->label(),
            ));*/
        //$form_state->setRedirect('entity.recrutement.collection');
    }

}
