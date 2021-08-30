<?php
namespace CiviCARROT;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

class PostResultsBackToMergeRequest extends WebDriverTestBase {

  /**
   * Required modules.
   *
   * @var array
   */
  protected static $modules = [];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function testComment() {
    $x = getenv('DRUPALGIT_PASS');
    if (empty($x)) {
      throw new \Exception('env not work');
    }
    $this->drupalGet('https://www.drupal.org/user/login?destination=gitlab/jwt');
    $this->getSession()->getPage()->fillField('name', 'civicarrot@gmail.com');
    $this->getSession()->getPage()->fillField('pass', getenv('DRUPALGIT_PASS'));
    $this->getSession()->getPage()->pressButton('edit-submit');
    $this->assertSession()->pageTextContains('Your projects');

    // @todo Need to get this from action script via env
    $this->drupalGet('https://git.drupalcode.org/sandbox/semperit-1280944/-/merge_requests/2');
    $this->assertSession()->waitForElementVisible('css', 'div.note-form-actions button.btn-confirm');
    $this->getSession()->getPage()->fillField('note-body', getenv('RUNSTATUS') . ': test automated merge request comment');
    $this->getSession()->getPage()->pressButton('Comment');

    // If we quit now, then there is some kind of ajax running constantly and
    // the drupal tests don't like that during teardown. So go somewhere else
    // first.
    $this->drupalGet('http://127.0.0.1:8080');
  }

}
