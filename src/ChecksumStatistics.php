<?php

namespace Drupal\media_duplicates;

use Drupal\Core\Database\Connection;

/**
 * Common interface for queries against the checksum.
 */
class ChecksumStatistics {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new ChecksumStatistics.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Checksums with more than one entry against the published media entities.
   *
   * @return array
   *   Key is the checksum, value is the number of occurrences.
   */
  public function checksumsWithDuplicates() {
    $results = $this->database
      ->query('SELECT duplicates_checksum, count(*) as x FROM {media_field_data} WHERE duplicates_checksum IS NOT NULL GROUP by duplicates_checksum HAVING count(*) > 1 ORDER BY x DESC')
      ->fetchAllKeyed(0);
    return $results;
  }

  /**
   * The number of published media entities where the checksum is NULL.
   *
   * @return int
   *   The count.
   */
  public function numberOfMissingChecksums() {
    $count = $this->database
      ->select('media_field_data')
      ->condition('duplicates_checksum', NULL, 'IS')
      ->countQuery()
      ->execute()
      ->fetchField();
    return (int) $count;
  }

}
