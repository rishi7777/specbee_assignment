<?php

/**  
 * @file  
 * Contains Drupal\specbee_assignment\Form\SiteConfigForm.  
 */  

namespace Drupal\specbee_assignment\Form;  

use Drupal\Core\State\StateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Cache\CacheTagsInvalidator;




class SiteLocationTimezoneConfigForm extends FormBase {

  /**
   * @var EntityTypeManager $entityTypeManager
   * @var CacheTagsInvalidator $cacheTagsInvalidator
   * @var State $state
   */
  protected $entityTypeManager;
  protected $cacheTagsInvalidator;
  protected $state;


  /**
   * Class constructor.
   */
  public function __construct(EntityTypeManager $entityTypeManager, CacheTagsInvalidator $cacheTagsInvalidator, StateInterface $state) {

  	$this->entityTypeManager = $entityTypeManager;
    $this->cacheTagsInvalidator = $cacheTagsInvalidator;
    $this->state = $state;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container->get('entity_type.manager'),
      $container->get('cache_tags.invalidator'),
      $container->get('state'),
    );
  }


  /**  
   * {@inheritdoc}  
   * Returns the form’s unique ID.
   */  
  public function getFormId() {  
    return 'site_location_timezone_config_form';  
  } 


  /**  
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) { 

    // Getting different timezones from vocab timezone.
    $vid = 'timezone';
    $timezone_terms =$this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($timezone_terms as $term) {
      $timezones[$term->name] = $term->name;
    }

    // Country textfield.
    $form['country'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('Country'), 
      '#required' => TRUE, 
      '#default_value' => $this->state->get('country'),  
    ];  
    
    // City textfield.
    $form['city'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('City'), 
      '#required' => TRUE, 
      '#default_value' => $this->state->get('city'),  
    ];  

    // Timezone field.
    $form['timezone'] = [  
      '#type' => 'select',  
      '#title' => $this->t('Timezone'), 
      '#options' => $timezones,
      '#required' => TRUE, 
      '#default_value' => $this->state->get('timezone'),  
      ];  


    $form['submit'] = [
       '#type' => 'submit',
       '#value' => 'Save',
    ];
    
    return $form;

  } 



    /**  
   * {@inheritdoc}  
   */  
  public function submitForm(array &$form, FormStateInterface $form_state) {

  	$country = $form_state->getValue('country');
  	$city = $form_state->getValue('city');
  	$timezone = $form_state->getValue('timezone');

  	$this->state->set('country', $country);
  	$this->state->set('city', $city);
  	$this->state->set('timezone', $timezone);

     // Invalidated 'site_location_timezone_tag' cache tag. 
     $this->cacheTagsInvalidator->invalidateTags(['site_location_timezone_tag']);  

  } 	
 

}

