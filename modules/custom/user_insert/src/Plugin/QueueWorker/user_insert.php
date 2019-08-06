<?php

namespace Drupal\user_insert\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Queue Worker that sends a welcome email to registered users.
 *
 * @QueueWorker(
 *   id = "user_insert",
 *   title = @Translation("User Insert"),
 *   cron = {"time" = 100}
 * )
 */
class user_insert extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {

    $user = \Drupal\user\Entity\User::load($data);
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'user_insert';
    $key = 'create_user';
    $to = $user->get('mail')->value;
    $params['message'] = "welcome to our site";
    $params['user_title'] = "New registartion";
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    if ($result['result'] !== true) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
    }
    else {
      drupal_set_message(t('Your message has been sent.'));
    }
  }
}

