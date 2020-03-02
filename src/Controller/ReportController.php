<?php

namespace Drupal\media_duplicates\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Drupal\media_duplicates\ChecksumStatistics;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Media Duplicates Report page.
 *
 * Display a report about media entities that are duplicates for each other.
 */
class ReportController extends ControllerBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The checksum statistics service.
   *
   * @var \Drupal\media_duplicates\ChecksumStatistics
   */
  protected $checksumStatistics;

  /**
   * Constructs a new SystemController.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   Database service.
   * @param \Drupal\media_duplicates\ChecksumStatistics $checksum_statistics
   *   The checksum statistics service.
   */
  public function __construct(Connection $database, ChecksumStatistics $checksum_statistics) {
    $this->database = $database;
    $this->checksumStatistics = $checksum_statistics;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('media_duplicates.checksum_statistics')
    );
  }

  /**
   * Report overview.
   */
  public function overview() {
    $header = [
      $this->t('Checksum'),
      $this->t('Count'),
      $this->t('Entities'),
    ];
    $rows = [];

    $checksum_results = $this->checksumStatistics->checksumsWithDuplicates();

    if (empty($checksum_results)) {
      return [
        '#markup' => $this->t('You have no duplicate checksums.'),
      ];
    }

    $media_results = $this->database
      ->select('media_field_data', 'mfd')
      ->fields('mfd', ['mid', 'name', 'duplicates_checksum'])
      ->condition('duplicates_checksum', array_keys($checksum_results), 'IN')
      ->execute();
    foreach ($media_results as $media_record) {
      $checksum = $media_record->duplicates_checksum;
      if (!is_array($checksum_results[$checksum])) {
        $checksum_results[$checksum] = [
          'number' => $checksum_results[$checksum],
          'entities' => [],
        ];
      }
      $checksum_results[$checksum]['entities'][] = $media_record;
    }

    foreach ($checksum_results as $checksum => $record) {
      $row = [
        'checksum' => $checksum,
        'count' => $record['number'],
        'entities' => [
          'data' => [
            '#theme' => 'links',
            '#links' => [],
          ],
        ],
      ];

      foreach ($record['entities'] as $mfd_record) {
        $row['entities']['data']['#links'][] = [
          'title' => $mfd_record->name,
          'url' => Url::fromRoute('entity.media.canonical', ['media' => $mfd_record->mid]),
        ];
      }

      $rows[] = $row;
    }

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#sticky' => TRUE,
    ];
  }

}
