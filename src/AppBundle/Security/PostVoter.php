<?php

// src/AppBundle/Security/PostVoter.php
namespace AppBundle\Security;

use AppBundle\Entity\TodoList;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on TodoList objects inside this voter
        if (!$subject instanceof TodoList) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a TodoList object, thanks to supports
        /** @var TodoList $todoList */
        $todoList = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($todoList, $user);
            case self::EDIT:
                return $this->canEdit($todoList, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(TodoList $todoList, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($todoList, $user)) {
            return true;
        }

        // the TodoList object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return false;
    }

    private function canEdit(TodoList $todoList, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $todoList->getUser();
    }
}