<?php
/**
 * @package   NotifyActivation
 * @type      Plugin (User)
 * @version   1.0.0
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
        $isBeingActivated = $oldActivationKeyField && ! $newActivationKeyField;

        if($isBeingActivated) {
            return $this->createActivationNote($newUser['id'], $isNew);
        }
    }

    protected function createActivationNote($userID, $isNew)
    {
        $db = JFactory::getDbo();

        $category = $this->params->get('usercategory', 0);
        $loggedInUser = JFactory::getUser();

        $message = ($loggedInUser->id === $userID)
            ? $this->params->get('self_message', '')
            : $this->getAdminMessage($isNew, $loggedInUser);

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

    private function getAdminMessage($isNew, $adminUser)
    {
        $userLinkURL = "/administrator/index.php?option=com_users&view=user&layout=edit&id=".$adminUser->id;
        $userLink = "<a href='{$userLinkURL}' target='_blank'>{$adminUser->name}</a>";
        $messageRef = $isNew ? 'instant_message' : 'admin_message';
        return sprintf($this->params->get($messageRef, ''), $userLink);
    }
}
