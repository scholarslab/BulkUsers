<?php
/**
 * Bulk Users Controller
 */
class BulkUsers_IndexController extends Omeka_Controller_AbstractActionController
{

  public function indexAction() {
    if ($post = $_POST) {

      // Explode emails on new lines.
      $emails = explode("\n", $post['emails']);
      $role = $post['role'];

      foreach ($emails as $email) {

        if ($email) {
          $email = trim($email);
          $username = explode("@", $email);
          $userData = array(
            'email' => $email,
            'role' => $role,
            'username' => $username[0],
            'name' => $username[0],
            'active' => 0
          );

          $user = new User();
          $user->setPostData($userData);

          if ($user->save(false)) {
            $this->sendActivationEmail($user);
          } else {
            $errorEmails[] = $email;
          }
        }
      }

      if ($errorEmails) {
        $errorMessage = "There were problems adding the following emails:"
                      . implode($errorEmails, ', ');
        $this->_helper->flashMessenger($errorMessage, 'error');
      }
    }

    require_once BULKUSERS_PLUGIN_DIR . '/forms/add.php';
    $form = new BulkUsers_Form_Add;
    $this->view->form = $form;

  }


  /**
   * Copied whole-hog from Omeka's UsersController.
   */
  protected function sendActivationEmail($user)
      {
          $ua = new UsersActivations;
          $ua->user_id = $user->id;
          $ua->save();

          // send the user an email telling them about their new user account
          $siteTitle  = get_option('site_title');
          $from       = get_option('administrator_email');
          $body       = __('Welcome!')
                      ."\n\n"
                      . __('Your account for the %s repository has been created. Please click the following link to activate your account:',$siteTitle)."\n\n"
                      . WEB_ROOT . "/admin/users/activate?u={$ua->url}\n\n"
                      . __('%s Administrator', $siteTitle);
          $subject    = __('Activate your account with the %s repository', $siteTitle);

          $mail = new Zend_Mail();
          $mail->setBodyText($body);
          $mail->setFrom($from, "$siteTitle Administrator");
          $mail->addTo($user->email, $user->name);
          $mail->setSubject($subject);
          $mail->addHeader('X-Mailer', 'PHP/' . phpversion());
          try {
              $mail->send();
              return true;
          } catch (Zend_Mail_Transport_Exception $e) {
              $logger = $this->getInvokeArg('bootstrap')->getResource('Logger');
              if ($logger) {
                  $logger->log($e, Zend_Log::ERR);
              }
              return false;
          }
      }
  }
