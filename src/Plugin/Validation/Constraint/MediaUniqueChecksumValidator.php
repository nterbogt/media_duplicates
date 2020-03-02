<?php

namespace Drupal\media_duplicates\Plugin\Validation\Constraint;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\media\Entity\Media;
use Drupal\media_duplicates\ChecksumStatistics;
use Drupal\media_duplicates\Plugin\MediaDuplicatesChecksumPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the MediaUniqueChecksum constraint.
 */
class MediaUniqueChecksumValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The checksum plugin manager service.
   *
   * @var \Drupal\media_duplicates\Plugin\MediaDuplicatesChecksumPluginManager
   */
  protected $checksumPluginManager;

  /**
   * The checksum statistics service.
   *
   * @var \Drupal\media_duplicates\ChecksumStatistics
   */
  protected $checksumStatistics;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('config.factory'),
      $container->get('plugin.manager.media_duplicates.checksum'),
      $container->get('media_duplicates.checksum_statistics')
    );
  }

  /**
   * Constructs a new MediaUniqueChecksumValidator.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\media_duplicates\Plugin\MediaDuplicatesChecksumPluginManager $checksum_plugin_manager
   *   The checksum plugin manager.
   * @param \Drupal\media_duplicates\ChecksumStatistics $checksum_statistics
   *   The checksum statistics service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, MediaDuplicatesChecksumPluginManager $checksum_plugin_manager, ChecksumStatistics $checksum_statistics) {
    $this->configFactory = $config_factory;
    $this->checksumPluginManager = $checksum_plugin_manager;
    $this->checksumStatistics = $checksum_statistics;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint) {
    if (!$this->canSave($entity)) {
      $this->context->addViolation($constraint->notUnique, ['%value' => $entity->bundle->entity->label()]);
    }
  }

  /**
   * Can save this media entity.
   *
   * We always err on the side of caution. If we don't have a definite
   * match with the settings and the checksum, presume we can save.
   *
   * @param \Drupal\media\Entity\Media $entity
   *   The media entity that is being checked.
   *
   * @return bool
   *   Whether we can save this entity or not.
   */
  private function canSave(Media $entity) {
    $config = $this->configFactory->get('media_duplicates.settings');

    if (!$config->get('restrict_duplicates')) {
      return TRUE;
    }

    if (!$entity->isNew() && $config->get('restrict_new_media_only')) {
      return TRUE;
    }

    $id = $entity->getSource()->getPluginId();
    try {
      $checksum_plugin = $this->checksumPluginManager->createInstanceForMediaType($id);
      $checksum = $checksum_plugin->getChecksum($entity);
    }
    catch (\Exception $e) {
      return TRUE;
    }

    if ($checksum === NULL) {
      return TRUE;
    }

    return !$this->checksumStatistics->checksumExists($checksum, $entity->id());
  }

}
