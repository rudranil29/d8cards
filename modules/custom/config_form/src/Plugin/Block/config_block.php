<?php

namespace Drupal\config_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'config' Block.
 *
 * @Block(
 *   id = "config_block",
 *   admin_label = @Translation("config block"),
 *   category = @Translation("config_World"),
 * )
 */
class config_block extends BlockBase {

  /**
   * {@inheritdoc}
   */

  public function build() {
    $config = \Drupal::config('configuration_form.settings');
    $name = NULL;
    $age = NULL;
    $gender = NULL;
    $name = $config->get('name');
    $age = $config->get('age');

    $gender = $config->get('gender');

    $data = $name.'<br>'.$age.'<br>'.$gender;

    return [
      '#type' => 'markup',
      '#markup' => $data,
      '#cache' => [
        'max-age' => 0,
        ],
    ];

  }

}
