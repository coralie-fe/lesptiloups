<?php

namespace Drupal\recrutement\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;

/**
 * Form controller for Recrutement edit forms.
 *
 * @ingroup recrutement
 */
class RecrutementForm extends ContentEntityForm {


    /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\recrutement\Entity\Recrutement */

      // objet form devient un array et ne contient plus les form mode, ils sont copiés ds $form_state
      // ils sont ds la clé 'callback_object'

      $rec = $form_state->getBuildInfo();
      $ope= $rec['callback_object'] -> getOperation();

      if($form_state->getBuildInfo()['form_id'] == 'recrutement_add_form'){
          // ajouter 2 champ : nom du parent et prenom du parent à la volée (juste pour l'affichage)
          // ces champs serviront pour la creation du user
          if($this->currentUser()->isAnonymous() || !$this->currentUser()->getRoles(TRUE)){
          $form['nomparent_value']= array(
              '#type'=>           'textfield',
              '#title'=>          t('Nom du parent : '),
              '#Description'=>    t('Nom du parent'),
              '#size'=>           '40',
              '#maxlengh'=>       '128',
              '#required'=>       'TRUE',
              '#suffix'=> '<span class="text-message"></span>',

          );
          $form['prenomparent_value']= array(
              '#type'=>           'textfield',
              '#title'=>          t('Prénom du parent : '),
              '#Description'=>    t('Prénom du parent'),
              '#size'=>           '40',
              '#maxlengh'=>       '128',
              '#required'=>       'TRUE',
              '#suffix'=> '<span class="text-message"></span>',

          );
          }
      }
      else {
          //@TODO soit ajouter un field ou changer le form display avec un affichant le champ parent avec autocompete et un inline entity form + que ca envoit
          // toujours le mail si c un new user créé par ief
      }

      $account= $this->currentUser();
      $account_name= $account->getAccountName();
      $account_role= $account->getRoles();
      //kint($account_role);

      if($account_role[0] == "anonymous"){
          $rec['callback_object']= $this->setOperation('anonyme');
      }elseif ($account_role[0] == "authenticated" && $account_role[1] == "administrator"){
          $rec['callback_object']= $this->setOperation('default');
      }elseif ($account_role[0] == "authenticated" && $account_role[1] == "bureau"){
          $rec['callback_object']= $this->setOperation('bureau');
      }elseif ($account_role[0] == "authenticated" && $account_role[1] == "parent"){
          $rec['callback_object']= $this->setOperation('parent');
      }elseif ($account_role[0] == "authenticated" && $account_role[1] == "equipe_pedagogique"){
          $rec['callback_object']= $this->setOperation('equipe_pedagogique');
      }

      $form_state->setBuildInfo($rec);

    $form = parent::buildForm($form, $form_state);
      $entity = $this->entity;
      //kint($form); die();
      return $form;
  }

    /**
     * change @inheritdoc submitForm
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $rec = $form_state->getBuildInfo();
        $ope= $rec['callback_object'] -> getOperation();
        //kint($form_state);
        //kint($ope);die();
        //kint($form_state->getValue('field_parent'));die();

        // si on est en mode ajout
        if($form['#form_id'] == 'recrutement_add_form'){

            if($this->currentUser()->isAnonymous() || !$this->currentUser()->getRoles(TRUE)){
                $field_nom =  $form_state->getValue('nomparent_value');
                $field_prenom =  $form_state->getValue('prenomparent_value');
                $field_email =  $form_state->getValue(['field_email', '0', 'value']);
                $id_parent = $this->entity->createUser($field_nom,$field_prenom,$field_email);
            }
            else{
                $id_parent = $this->currentUser()->id();
            }

            //kint($id_parent); die();


            // ajouter $id_parent ds le champ reference parent
            $form_state->setValue(['field_parent', '0', 'target_id'],$id_parent);

            //$form['field_parent']['widget']['#default_value'] = array($id_parent);

            /*$form_state->setValues(array(
                    'field_parent'=> $id_parent,

                )
            );*/

            // si user anonyme statut en attente
            // si user bureau statut confirmé

            $form_state->setValue(['field_statut', '0', 'value'],'0');

        }


        parent::submitForm($form, $form_state);



    }


  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;
    $status = parent::save($form, $form_state);


    switch ($status) {
      case SAVED_NEW:
        /*drupal_set_message($this->t('Created the %label Recrutement.', [
          '%label' => $entity->label(),
        ]));*/
          drupal_set_message($this->t('Thank you for your inscription @name', array('@name' => $form_state->getValue('field_nom'))));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Recrutement.', [
          '%label' => $entity->label(),
        ]));
    }


    $form_state->setRedirect('entity.recrutement.canonical', ['recrutement' => $entity->id()]);
  }

    //fonction de test validant le formulaire
    public function send(array $form, FormStateInterface $form_state)
    {
        //Récupération de l'objet de stockage du noeud avec une condition de creation
        $recrutement = \Drupal::entityTypeManager()->getStorage('recrutement')->create(array('type' => 'recrutement'));
        $user = \Drupal::entityTypeManager()->getStorage('user');
        //Affection de la fonction getSendMail dans la variable $recrutement issue de la classe Recrutement.php
        $recrutement->getSendMail();
        //Récupération de tous les identifiants d'un type d'entité avec une condition sur le statut
        $ids = \Drupal::entityQuery('user')->condition('status', '0', '=')->execute();
        //Chargement des objets correspondants
        $users = $user->loadMultiple($ids);
        //Pour chaque utilisateurs, afficher le statut, le changer et l'envoyer
        foreach ($users as $usr) {
            if ($usr->isActive() == 0) {
                $usr->set('status', 1);//Changement du statut
                $usr->save();//Sauvegarder le changement de statut
                $recrutement->getSendMail();//envoie du mail quand le statut est modifié
            }
        }

        /*fonction de test

        foreach($users as $usr) {
            kint($usr->isActive());
            $usr->set('status',0);
            $usr->save();

            kint($usr->isActive());

        }*/
    }


}
