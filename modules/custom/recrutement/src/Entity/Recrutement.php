<?php

namespace Drupal\recrutement\Entity;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Render\AttachmentsInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\recrutement\RecrutementInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Mail\MailManagerInterface;

/**
 * Defines the Recrutement entity.
 *
 * @ingroup recrutement
 *
 * @ContentEntityType(
 *   id = "recrutement",
 *   label = @Translation("Recrutement"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\recrutement\RecrutementListBuilder",
 *     "views_data" = "Drupal\recrutement\Entity\RecrutementViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\recrutement\Form\RecrutementForm",
 *       "test" = "Drupal\recrutement\Form\RecrutementForm",
 *       "add" = "Drupal\recrutement\Form\RecrutementForm",
 *       "edit" = "Drupal\recrutement\Form\RecrutementForm",
 *       "delete" = "Drupal\recrutement\Form\RecrutementDeleteForm",
 *       "anonyme" = "Drupal\recrutement\Form\RecrutementForm",
 *       "bureau" = "Drupal\recrutement\Form\RecrutementForm",
 *       "parent" = "Drupal\recrutement\Form\RecrutementForm",
 *       "equipe_pedagogique" = "Drupal\recrutement\Form\RecrutementForm",
 *       "confirm" = "Drupal\recrutement\Form\RecrutementConfirmForm",
 *       "archive" = "Drupal\recrutement\Form\RecrutementArchiveForm",

 *     },
 *     "access" = "Drupal\recrutement\RecrutementAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\recrutement\RecrutementHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "recrutement",
 *   admin_permission = "administer recrutement entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/recrutement/{recrutement}",
 *     "add-form" = "/admin/structure/recrutement/add",
 *     "edit-form" = "/admin/structure/recrutement/{recrutement}/edit",
 *     "delete-form" = "/admin/structure/recrutement/{recrutement}/delete",
 *     "confirm-form" = "/admin/structure/recrutement/{recrutement}/confirm",
 *     "archive-form" = "/admin/structure/recrutement/{recrutement}/archive",
 *     "collection" = "/admin/structure/recrutement",
 *   },
 *   field_ui_base_route = "recrutement.settings"
 * )
 */
class Recrutement extends ContentEntityBase implements RecrutementInterface {
  use EntityChangedTrait;
  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Recrutement entity.'))
      ->setReadOnly(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Recrutement entity.'))
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Recrutement entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Recrutement entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Recrutement is published.'))
      ->setDefaultValue(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code for the Recrutement entity.'))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

    /**
     * create user with status  blocked (0)
     */
    public function createUser($name,$email) {
        $user_id= "";
        $user = \Drupal\user\Entity\User::create();
kint($name);
        kint($email); die();
//Mandatory settings
        $user->setPassword('passworrrd'); // note : faire la generation pass aleatoire
        $user->enforceIsNew();
        $user->setEmail("iii@free.fr");
        // note : voir plain texte pour name sur  validate du form
        $user->setUsername("iii");//This username must be unique and accept only a-Z,0-9, - _ @ .
        //$user->set('status',0);

        //$user->addRole('parent');

        //$user->set('status')->value = 0;
        //$user->activate();


//Optional settings
        /*$user->set("init", 'email');
        $user->set("langcode", $language);
        $user->set("preferred_langcode", $language);
        $user->set("preferred_admin_langcode", $language);
        //$user->set("setting_name", 'setting_value');
        $user->activate();*/

//Save user
        //$res = $user->save();
        return $user_id;



    }

    //fonction d'envoi d'email

    /**
     * @return string
     */
    public function getSendMail()
    {
        //Recupération de l'uid utilisateur inscris
        //$uid = $this->getOwner();
        $mailManager = \Drupal::service('plugin.manager.mail');//Chargement du service plugin manager mail
        $module = 'recrutement';//nom du module utilisé
        $key = 'recrutement_mail';//La clé du mail
        $to = \Drupal::currentUser()->getEmail();//Le destinataire du mail
        $param['message'] = "Bonjour";//Message en dure
        $param['title'] = "Bonsoir";//Titre en dure
        $langcode = \Drupal::currentUser()->getPreferredLangcode();//Langue
        $send = true;

        $result = $mailManager->mail($module, $key, $to, $langcode, $param, NULL, $send);//préparation du mail
        //Si le mail nest pas envoyé
        if ($result != true)  {
            $message = t('Nous avons un problème dans lenvoi du mail à @email.', array('@email' => $to));
            drupal_set_message($message, 'error');
            \Drupal::logger('mail-log')->error($message);
            return;
        }
        //Sinon si le mail a bien été envoyé
        $message = t('Une nouvelle notification a été envoyé à @email ', array('@email' => $to));
        drupal_set_message($message, 'status');
        \Drupal::logger('mail-log')->notice($message);
    }

    public function changeRecrutementStatus() {
        $a  = $this->get('status');
        $b = $a->getValue();
         $a->setValue(2);
        $c = $a->getValue();
        $this->set('status', 2);
        $a  = $this->get('status');
        $b = $a->getValue();
        $this->get('status')->setValue(2);
        $this->save();
    }

}
