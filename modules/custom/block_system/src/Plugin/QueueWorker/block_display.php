<?php

namespace Drupal\block_system\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 *
 *
 * @QueueWorker(
 *   id = "block_system",
 *   title = @Translation("Share Market"),
 *   cron = {"time" = 120}
 * )
 */
class block_display extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  protected $blockStorage;

  protected $httpClient;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityStorageInterface $block_storage, Client $http_client) {

    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->blockStorage = $block_storage;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition ) {
    return new static (
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')->getStorage('block_content'),
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {

    $block = $this->blockStorage->load($data);
    $symbol = $block->get('field_symbol')->value;
    $data = $this->block_api_data($symbol);
    $block->set('field_last_price', $data['LastPrice']);
    $block->set('field_change', $data['Change']);
    return $block->save();
  }

  public function block_api_data($symbol) {

    $url = 'http://dev.markitondemand.com/MODApis/Api/v2/Quote/jsonp?symbol=' . $symbol . '&callback=myFunction';
    $response = $this->httpClient->request('GET', $url);

    if ($response->getStatusCode() == 200) {
      $response = (string) $response->getBody();

      if (strpos($response, 'myFunction({') === 0) {
        $response = substr($response, 11, -1);
        $response = @json_decode($response, TRUE);

        if ($response['Status'] == 'SUCCESS') {
          return $response;
        }
      }
    }
  }

}
