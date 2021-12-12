<?php

namespace Drupal\digitalist_test\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Non-Drupal class to query Systembevakningsagenten.se API.
 *
 * API monitors beers from System bolaget.
 */
class BeveragesApi {

  /**
   * Drupal logger service set to digitalist_test channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;


  /**
   * Guzzle HTTP client service.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Logger channel factory service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Config Factory Interface.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(
    LoggerChannelFactoryInterface $logger_factory,
    ConfigFactoryInterface $config_factory,
    ClientInterface $http_client
  ) {
    $this->logger = $logger_factory->get('digitalist_test');
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
  }

  /**
   * Class constructor.
   *
   * @param string $searchTerm
   *   Query search term.
   *
   * @return array
   *   List of API results based on $searchTerm or empty array if nothing was
   *    found for that term.
   */
  public function get(string $searchTerm): array {

    if (strlen(trim($searchTerm)) === 0) {
      // There is no point in calling the API if no term is provided, it will
      // just return 0 results.
      return [];
    }

    $apiGETUrl = $this->configFactory->get('digitalist_test.beverages_search_settings')
      ->get('GET_url');

    try {

      $response = $this->httpClient->request("GET", $apiGETUrl, [
        "query" => [
          "query" => $searchTerm,
        ],
      ]);

      $json = json_decode($response->getBody()->getContents(), TRUE);
      return $json['items'];

    }
    catch (GuzzleException $exception) {

      $this->logger->error("Unexpected error has occurred while fetching: @url. Exception was of type GuzzleHttp\Exception\GuzzleException. Exception message is @exception", [
        'url' => $apiGETUrl,
        'exception' => $exception->getMessage(),
      ]);

      return [];

    }
  }

}
