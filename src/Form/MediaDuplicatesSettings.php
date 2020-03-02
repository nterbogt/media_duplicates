<?php

namespace Drupal\media_duplicates\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the media duplicates settings form.
 */
class MediaDuplicatesSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'media_duplicates_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['media_duplicates.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory->get('media_duplicates.settings');

    $form['restrict_duplicates'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Restrict users from creating duplicate media items.'),
      '#default_value' => $config->get('restrict_duplicates'),
    ];

    $form['restrict_new_media_only'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Only restrict duplicates on new media items.'),
      '#description' => $this->t('This is useful if you already have duplicate items and you want your users to continue editing them, but don\'t want new duplicates to be added.'),
      '#default_value' => $config->get('restrict_new_media_only'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('media_duplicates.settings');

    $variables = [
      'restrict_duplicates',
      'restrict_new_media_only',
    ];
    foreach ($variables as $variable) {
      $config->set($variable, $form_state->getValue($variable));
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }
}
