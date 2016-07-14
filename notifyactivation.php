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
    public function onUserBeforeSave($oldUser, $isNew, $newUser)
    {
        $oldActivationKeyField = isset($oldUser['activation']) ? $oldUser['activation'] : '';
        $newActivationKeyField = isset($newUser['activation']) ? $newUser['activation'] : '';

        //the act of activating the account causes the activation key field to be cleared.
        $isBeingActivated = $oldActivationKeyField && !$newActivationKeyField;

        if($isBeingActivated) {
            return $this->createActivationNote($newUser['id'], false);
        }
    }

    public function onUserAfterSave($newUser, $isNew, $success)
    {
        //for new users that are active immediately on creation, we have to log this after the user record is saved
        //because otherwise we won't have the userID.
        if($success && $isNew && !$newUser['activation']) {
            return $this->createActivationNote($newUser['id'], true);
        }
    }

    protected function createActivationNote($userID, $instantActive)
    {
        $db = JFactory::getDbo();

        $category = $this->params->get('usercategory', 0);
        $loggedInUser = JFactory::getUser();

        $message = ($loggedInUser->id === 0)
            ? $this->params->get('self_message', '')
            : $this->getAdminMessage($instantActive, $loggedInUser);

        $fields = (object)[
            'user_id'           => $userID,
            'catid'             => (int)$category,
            'subject'           => $this->params->get('subject', ''),
            'body'              => "<div>{$message}</div>",
            'state'             => 1,
            'created_user_id'   => $loggedInUser->id,
            'created_time'      => date('Y-m-d H:i:s'),
            'modified_user_id'  => $loggedInUser->id,
            'modified_time'     => date('Y-m-d H:i:s'),
            'review_time'       => date('Y-m-d'),
        ];

        $result = $db->insertObject('#__user_notes', $fields);
    }

    private function getAdminMessage($instantActive, $adminUser)
    {
        $userLinkURL = "/administrator/index.php?option=com_users&view=user&layout=edit&id=".$adminUser->id;
        $userLink = "<a href='{$userLinkURL}' target='_blank'>{$adminUser->name}</a>";
        $messageRef = $instantActive ? 'instant_message' : 'admin_message';
        return sprintf($this->params->get($messageRef, ''), $userLink);
    }
}
