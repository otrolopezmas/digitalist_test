<?php

namespace Drupal\digitalist_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a Digitalist test form.
 */
class SearchBeveragesForm extends FormBase {

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * The controller constructor.
   */
  public function __construct(Request $current_request) {
    $this->currentRequest = $current_request;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'digitalist_test_search_api';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $querySearchTerm = $this->currentRequest->query->get("query") ?? "";

    return $form + [
      'searchTerm' => [
        '#type' => 'textfield',
        '#title' => $this->t('Search by'),
        '#default_value' => $querySearchTerm,
        '#required' => TRUE,
      ],
      'actions' => [
        '#type' => 'actions',
        'submit' => [
          '#type' => 'submit',
          '#value' => $this->t('Search'),
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $searchInputValue = $form_state->getValue('searchTerm');

    $form_state->setRedirect('digitalist_test.search_page', [], [
      'query' => [
        'query' => $searchInputValue,
      ],
    ]);
  }

}
