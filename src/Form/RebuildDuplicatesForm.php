<?php

namespace Drupal\media_duplicates\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\media_duplicates\MediaDuplicatesChecksumBatch;

/**
 * Form for rebuilding permissions.
 *
 * @internal
 */
class RebuildDuplicatesForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'media_duplicates_refresh_checksums';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to rebuild the checksums on media?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('system.status');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Rebuild checksums');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('This action rebuilds all checksums on media, and may be a lengthy process. This action cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    batch_set(MediaDuplicatesChecksumBatch::tasks());
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
