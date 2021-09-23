<?php

namespace Drupal\specbee_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\specbee_assignment\GetCurrentTimeByTimezone;


/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "show_site_location_time_block",
 *   admin_label = @Translation("Show site's location and current time."),
 * )
 */
class ShowSiteLocationTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {
   /**
    * @var GetCurrentTimeByTimezone $currentTime.
    * @var State $state.
    */
   protected $currentTime;
   protected $state;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param Drupal\assignment\GetCurrentTimeByTimezone $currentTime;
   * @param Drupal\Core\State\StateInterface $state;
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GetCurrentTimeByTimezone $currentTime, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentTime = $currentTime;
    $this->state = $state;
  }
  
  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('specbee_assignment.get_current_time_by_timezone'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'show_site_location_timezone',
      '#country' => $this->state->get('country'),
      '#city' => $this->state->get('city'),
      '#time' => $this->currentTime->getTimeWithTimezone(),
      '#cache' => [
        'tags' => ['site_location_timezone_tag'],
      ],
    ];
  }

}
