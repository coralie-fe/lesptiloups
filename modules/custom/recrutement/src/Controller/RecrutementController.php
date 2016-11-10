<?php
/**
 * @
 */

namespace Drupal\recrutement\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;

class RecrutementController extends ControllerBase {

    public function content() {

        // uder
        $account= $this->currentUser();
        $account_name= $account->getAccountName();
        $account_role= $account->getRoles();
        kint($account_role);

        //$form_modes = \Drupal::entityManager()->getAllFormModes();
        //kint($form_modes);

        // form mode
        $recrutement = $this->entityManager()->getStorage('recrutement')->create(array(
            'type' => 'recrutement'
        ));

        // penser à ajouter les form mode créer en back office dans les annotations ds recrutement.php
        // selon user affiche form mode correspondant
            // note : on pourrait lister tous les form mode de l'entitée, puis créer des permissions sur ceux-ci
            // et gerer les permissions en back office
        if($account_role[0] == "anonymous"){
            $form = $this->entityFormBuilder()->getForm($recrutement, 'anonyme');
        }elseif ($account_role[0] == "authenticated" && $account_role[1] == "administrator"){
            $form = $this->entityFormBuilder()->getForm($recrutement, 'default');
        }elseif ($account_role[0] == "authenticated" && $account_role[1] == "bureau"){
            $form = $this->entityFormBuilder()->getForm($recrutement, 'bureau');
        }elseif ($account_role[0] == "authenticated" && $account_role[1] == "parent"){
            $form = $this->entityFormBuilder()->getForm($recrutement, 'parent');
        }elseif ($account_role[0] == "authenticated" && $account_role[1] == "equipe_pedagogique"){
            $form = $this->entityFormBuilder()->getForm($recrutement, 'equipe_pedagogique');
        }


        return $form;


    }


}
