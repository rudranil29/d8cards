<?php

namespace Drupal\config_form\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class Configurationform extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */

  public function getFormId() {
    return 'configuration_form_settings';
  }

  /**
   * {@inheritdoc}
   */

  protected function getEditableConfigNames() {
    return ['configuration_form.settings'];
  }

  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('NAME'),
      '#default_value' => $this->config('configuration_form.settings')->get('name'),
      '#required' => TRUE,
    ];

    $form['age'] = [
      '#type' => 'select',
      '#title' => $this->t('AGE'),
      '#options' => [25 => '25', 30 => '30', 35 =>'35'],
      '#default_value' => $this->config('configuration_form.settings')->get('age'),
      '#required' => TRUE,
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('GENDER'),
      '#options' => [MALE => 'MALE', FEMALE => 'FEMALE', OTHERS => 'OTHERS'],
      '#default_value' => $this->config('configuration_form.settings')->get('gender'),
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('configuration_form.settings')
      ->set('name', $form_state->getValue('name'))
      ->set('age', $form_state->getValue('age'))
      ->set('gender', $form_state->getValue('gender'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
