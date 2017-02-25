<?php

namespace Drupal\chep_contact;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\domain\DomainNegotiatorInterface;

/**
 * The ChepContact service.
 *
 * @package Drupal\chep_contact
 */
class ChepContact implements ChepContactInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  /**
   * @var string
   */
  protected $currentLanguageCode;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, QueryFactory $entityQuery, LanguageManagerInterface $languageManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->entityQuery = $entityQuery;
    $this->currentLanguageCode = $languageManager->getCurrentLanguage()->getId();
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentContactNode() {
    static $node = NULL;

    if (!isset($node)) {
      $nids = $this->entityQuery->get('node')
        ->condition('type', 'contact')
        ->condition('status', 1)
        ->addTag('node_access')
        ->range(0, 1)
        ->execute();

      if ($nids) {
        /** @var \Drupal\node\Entity\Node $node */
        $node = $this->entityTypeManager
          ->getStorage('node')
          ->load(reset($nids));
        if ($node->hasTranslation($this->currentLanguageCode)) {
          $node = $node->getTranslation($this->currentLanguageCode);
        }
      }
    }

    return $node;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhoneData($key = '') {
    $data = [];

    $node = $this->getCurrentContactNode();
    if ($node) {

      $phoneNumber = $node->field_contact_main_phone->value;
      $url = $node->toUrl()->toString();

      $data = [
        'phone' => $phoneNumber,
        'url' => $url,
      ];
    }
    else {
      return NULL;
    }

    return $key ? $data[$key] : $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getContactFormUrl() {
    $url = '';

    $node = $this->getCurrentContactNode();
    if ($node) {
      $url = $node->toUrl()->toString();
    }

    return $url;
  }

  /**
   * {@inheritdoc}
   */
  public function getContactUrl() {
    $url = NULL;

    $node = $this->getCurrentContactNode();
    if ($node) {
      $url = $node->toUrl();
    }

    return $url;
  }

}
