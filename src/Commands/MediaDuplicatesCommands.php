<?php

namespace Drupal\media_duplicates\Commands;

use Drush\Commands\DrushCommands;
use Drupal\media\Entity\Media;

/**
 * Media duplicates drush commands.
 */
class MediaDuplicatesCommands extends DrushCommands {

  /**
   * Rebuild all the checksums for media revisions.
   *
   * Useful for initial installs on existing sites or if you change the
   * algorithms, or install plugins.
   *
   * @command media-duplicates:refresh-checksums
   *
   * @validate-module-enabled media_duplicates
   */
  public function refreshChecksums() {
    batch_set($this->batchTasks());
    $batch =& batch_get();
    $batch['progressive'] = TRUE;
    drush_backend_batch_process();
  }

  /**
   * Build the batch processing definition.
   *
   * @return array
   *   The tasks to run.
   */
  public function batchTasks() {
    $batch = [
      'title' => t('Updating checksums for media entities...'),
      'operations' => [],
      'finished' => [static::class, 'batchFinished'],
    ];

    $media_ids = \Drupal::entityQuery('media')
      ->execute();
    foreach ($media_ids as $id) {
      $batch['operations'][] = [
        [static::class, 'updateMediaEntity'],
        [$id],
      ];
    }

    return $batch;
  }

  /**
   * Process a single media entity.
   *
   * @param int $id
   *   The ID of the entity to process.
   * @param mixed $context
   *   The batch context so we can have result counts and messages.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function updateMediaEntity($id, &$context) {
    $entity = Media::load($id);
    $entity->save();
    $context['results'][] = $id;
  }

  /**
   * Finished callback.
   *
   * @param bool $success
   *   TRUE if succeeded.
   * @param array $results
   *   Successfully deleted revision IDs.
   * @param array $operations
   *   Operations.
   */
  public static function batchFinished($success, array $results, array $operations) {
    if ($success) {
      $message = t('Finished updating media entities, @count items were processed.', [
        '@count' => count($results),
      ]);
      \Drupal::messenger()->addMessage($message);
    }
    else {
      // An error occurred.
      // $operations contains the operations that remained unprocessed.
      $error_operation = reset($operations);
      $message = t('An error occurred while processing %error_operation with arguments: @arguments', [
        '%error_operation' => implode('::', $error_operation[0]),
        '@arguments' => print_r($error_operation[1], TRUE),
      ]);
      \Drupal::messenger()->addError($message);
    }
  }

}
