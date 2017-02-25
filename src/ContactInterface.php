<?php

namespace Drupal\contact;

/**
 * Interface ContactInterface.
 *
 * @package Drupal\contact
 */
interface ContactInterface {

  /**
   * Get the Contact page node of the current domain and language.
   *
   * @return \Drupal\node\Entity\Node
   */
  public function getCurrentContactNode();

  /**
   * Get phone number data.
   *
   * @param string $key
   *
   * @return string|array
   */
  public function getPhoneData($key = '');

  /**
   * Get contact form URL of current domain.
   *
   * @return string
   */
  public function getContactFormUrl();

  /**
   * Get contact form URL.
   *
   * @return \Drupal\Core\Url
   */
  public function getContactUrl();

}
