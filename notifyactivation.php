<?php
/**
 * @package   NotifyActivation
 * @type      Plugin (User)
 * @version   1.0.1
 * @author    Simon Champion
 * @copyright (C) 2016 Simon Champion
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

class plgUserNotifyActivation extends JPlugin
{
    const MODE_USER_INSTANT_ACTIVATION  = 'user_instant_activation';
    const MODE_ADMIN_INSTANT_ACTIVATION = 'admin_instant_activation';
    const MODE_USER_EMAIL_VERIFICATION  = 'user_email_verification';
    const MODE_USER_EMAIL_ACTIVATION    = 'user_email_activation';
    const MODE_ADMIN_EMAIL_ACTIVATION   = 'admin_email_activation';
    const MODE_ADMIN_PANEL_ACTIVATION   = 'admin_panel_activation';

    public function onUserBeforeSave($oldUser, $isNew, $newUser)
    {
        $oldActivationKey = isset($oldUser['activation']) ? $oldUser['activation'] : '';
        $newActivationKey = isset($newUser['activation']) ? $newUser['activation'] : '';

        //the act of activating the account causes the activation key field to be cleared.
        $isBeingActivated = $oldActivationKey && !$newActivationKey;

        //if activation key changed but is still set then user has verified, but admin needs to activate them.
        $isBeingVerified = ($oldActivationKey && $newActivationKey) && ($oldActivationKey != $newActivationKey);

        if($isBeingActivated) {
            return $this->createActivationNote($newUser['id'], $this->getActivationMode($oldUser['params']));
        } elseif($isBeingVerified) {
            return $this->createActivationNote($newUser['id'], self::MODE_USER_EMAIL_VERIFICATION);
        }
    }

    public function onUserAfterSave($newUser, $isNew, $success)
    {
        //for new users that are active immediately on creation, we have to log this after the user record is saved
        //because otherwise we won't have the userID.
        if($success && $isNew && !$newUser['activation']) {
            $loggedInUser = JFactory::getUser();
            $mode = ($loggedInUser->id > 0) ? self::MODE_ADMIN_INSTANT_ACTIVATION : self::MODE_USER_INSTANT_ACTIVATION;
            return $this->createActivationNote($newUser['id'], $mode);
        }
    }

    private function getActivationMode($params)
    {
        //distinguish between clicking on token in email and clicking activate button in admin.
        $input = JFactory::getApplication()->input;
        $usingToken = ($oldActivationKey && $input->get('token') === $oldActivationKey);

        $userParams = json_decode($params, true);
        if (!isset($userParams['activate'])) {
            $userParams['activate'] = 0;
        }
        
        $componentParams = JComponentHelper::getParams('com_users');
        $activationByAdmin = $componentParams->get('useractivation') == 2 && $userParams['activate'];

        $loggedInUser = JFactory::getUser();
        if ($loggedInUser->id > 0 && !$usingToken) {
            return self::MODE_ADMIN_PANEL_ACTIVATION;
        }

        return $activationByAdmin ? self::MODE_ADMIN_EMAIL_ACTIVATION : self::MODE_USER_EMAIL_ACTIVATION;
    }

    protected function createActivationNote($userID, $activationMode)
    {
        $db = JFactory::getDbo();

        $category = $this->params->get('usercategory', 0);
        $loggedInUser = JFactory::getUser();

        $message = $this->getAdminMessage($activationMode, $loggedInUser);

        $fields = (object)[
            'user_id'           => $userID,
            'catid'             => (int)$category,
            'subject'           => $this->params->get('subject', ''),
            'body'              => "<div>{$message}</div>",
            'state'             => 1,
            'created_user_id'   => $loggedInUser->id ?: 1,
            'created_time'      => date('Y-m-d H:i:s'),
            'modified_user_id'  => $loggedInUser->id ?: 1,
            'modified_time'     => date('Y-m-d H:i:s'),
            'review_time'       => date('Y-m-d'),
        ];

        $result = $db->insertObject('#__user_notes', $fields);
    }

    private function getAdminMessage($activationMode, $adminUser)
    {
        $userLinkURL = "/administrator/index.php?option=com_users&view=user&layout=edit&id=".$adminUser->id;
        $userLink = "<a href='{$userLinkURL}' target='_blank'>{$adminUser->name}</a>";
        return sprintf($this->params->get($activationMode, ''), $userLink);
    }
}
