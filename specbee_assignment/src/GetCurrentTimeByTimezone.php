<?php

/**
* @file providing the service that return time by timezone.
*
*/

namespace  Drupal\specbee_assignment;

use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\State\StateInterface;


class GetCurrentTimeByTimezone {
 
 /**
  * @var DateFormatter $dateFormatter.
  * @var State $state
  */ 
 protected $dateFormatter;
 protected $state;

 
 /**
   * @param Drupal\Core\Datetime\DateFormatter $dateFormatter;
   */
 public function __construct(DateFormatter $dateFormatter, StateInterface $state) {
   $this->dateFormatter = $dateFormatter;
   $this->state = $state;
 }

 public function  getTimeWithTimezone(){

    // Get country, city and timezone values which is set in site config form.
    $country = $this->state->get('country');
    $city = $this->state->get('city');
    $timezone = $this->state->get('timezone');  
    if($country && $city && $timezone){

      // Use dateFormatter service to get time by timezone.
      $date_time = $this->dateFormatter->format(time(), 'custom', 'jS M Y - h:i A', $timezone);
      return $date_time;
    }
 }
}