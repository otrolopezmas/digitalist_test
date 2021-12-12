<?php

namespace Drupal\digitalist_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\digitalist_test\Service\BeveragesApi;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Pager\PagerManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for the /search-beverage route.
 *
 * Builds a page with SearchBeveragesForm and search-beverages-results.html.twig
 * template for results.
 */
class BeveragesSearchController extends ControllerBase {

  /**
   * Form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Pager Manager service.
   *
   * @var Drupal\Core\Pager\PagerManager
   */
  protected $pagerManager;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * The API service.
   *
   * @var Drupal\digitalist_test\Api\BeveragesApi
   */
  protected $beveragesApi;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   Form builder service.
   * @param \Drupal\Core\Pager\PagerManager $pager_manager
   *   Pager manager service.
   * @param \Symfony\Component\HttpFoundation\Request $current_request
   *   Current page request.
   * @param \Drupal\digitalist_test\Service\BeveragesApi $beverages_api
   *   Beverages API service.
   */
  public function __construct(
    FormBuilderInterface $form_builder,
    PagerManager $pager_manager,
    Request $current_request,
    BeveragesApi $beverages_api
  ) {
    $this->formBuilder = $form_builder;
    $this->pagerManager = $pager_manager;
    $this->currentRequest = $current_request;
    $this->beveragesApi = $beverages_api;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder'),
      $container->get('pager.manager'),
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('digitalist_test.beverages_api'),

    );
  }

  /**
   * Main returning function for this controller.
   *
   * Builds a Render array that uses the search-beverages-template to display
   * the results of querying \Drupal\digitalist-test\Service\BeveragesApi.
   *
   * @return array[]
   *   Drupal render array with the right template and pager.
   */
  public function build(): array {

    $searchForm = $this->formBuilder->getForm('\Drupal\digitalist_test\Form\SearchBeveragesForm');

    $querySearchTerm = $this->currentRequest->query->get("query") ?? "";
    $searchResults = $this->beveragesApi->get($querySearchTerm);

    // Handle pager.
    $totalResults = count($searchResults);
    $numberOfElementsPerPage = $this->config("digitalist_test.beverages_search_settings")
      ->get("pager_number_items_page") ?? 5;

    $currentPage = $this->pagerManager->createPager($totalResults, $numberOfElementsPerPage)
      ->getCurrentPage();
    $pagedResults = array_slice($searchResults, ($currentPage * $numberOfElementsPerPage), $numberOfElementsPerPage);

    return [
      'results' => [
        '#theme' => 'search_beverages_results',
        '#var_search_form' => $searchForm,
        '#var_search_term' => $querySearchTerm,
        '#var_search_results' => $pagedResults,
      ],
      'pager' => [
        '#type' => 'pager',
        '#quantity' => ($totalResults / $numberOfElementsPerPage),
      ],
    ];

  }

}
