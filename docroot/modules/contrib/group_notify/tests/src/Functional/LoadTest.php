<?php

namespace Drupal\Tests\group_notify\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Basic test to ensure that main page loads with this module enabled.
 *
 * @group group_notify
 */
class LoadTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'group_notify',
    // There's an implicit dependency for the comment view mode we install.
    // @todo Untangle this -- either by only conditionally installing that, or
    //   explicitly declaring the dependency.
    'comment',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->drupalLogin($this->drupalCreateUser(['administer site configuration']));
  }

  /**
   * Tests that the home page loads with a 200 response.
   */
  public function testLoad() {
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->statusCodeEquals(200);
  }

}
