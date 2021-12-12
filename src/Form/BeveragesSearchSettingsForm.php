<?php

namespace Drupal\digitalist_test\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Admin form to handle general settings of the Beverages search functionality.
 */
class BeveragesSearchSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'digitalist_test.beverages_search_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'beverages_search_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);
    $config = $this->config('digitalist_test.beverages_search_settings');

    return $form + [
      'GET_url' => [
        '#type' => 'textfield',
        '#title' => $this->t('API GET method base URL'),
        '#default_value' => $config->get('GET_url') ?? '',
        '#required' => TRUE,
      ],
      'pager_number_items_page' => [
        '#type' => 'number',
        '#title' => $this->t('Number of items per page'),
        '#default_value' => $config->get('pager_number_items_page') ?? 5,
        '#required' => TRUE,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('digitalist_test.beverages_search_settings')
      ->set('GET_url', $form_state->getValue('GET_url'))
      ->set('pager_number_items_page', $form_state->getValue('pager_number_items_page'))
      ->save();
  }

}
