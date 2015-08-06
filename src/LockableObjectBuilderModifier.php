<?php

/**
 * @author Cedric LOMBARDOT <cedric@donkeycode.com>
 */
class LockableObjectBuilderModifier
{
    /**
     * @var LockableBehavior
     */
    private $behavior;

    public function __construct(Behavior $behavior)
    {
        $this->behavior = $behavior;
    }

    public function objectAttributes($builder)
    {
        return $this->behavior->renderTemplate('objectVars', array(
            'locker_activated' => $this->behavior->getParameters()['locker_activated'],
        ));
    }

    public function addLocker()
    {
        return $this->behavior->renderTemplate('addLocker', array(
            'apply_to' => $this->behavior->getApplyToFields(),
            'setters'  => $this->getApplyToSetters(),
            'getters'  => $this->getApplyToGetters(),
        ));
    }

    public function objectMethods($builder)
    {
        $script  = '';

        $script .= $this->addLocker($builder);

        return $script;
    }

    public function preSave()
    {
        return $this->behavior->renderTemplate('preSave', array(
        ));
    }

    public function postSave()
    {
        return $this->behavior->renderTemplate('postSave', array(
        ));
    }

    protected function getApplyToSetters()
    {
        $getters = array();
        foreach ($this->behavior->getApplyToFields() as $field) {
            $getters[$field] = 'set'.$this->behavior->getTable()->getColumn($field)->getPhpName();
        }

        return $getters;
    }

    protected function getApplyToGetters()
    {
        $getters = array();
        foreach ($this->behavior->getApplyToFields() as $field) {
            $getters[$field] = 'get'.$this->behavior->getTable()->getColumn($field)->getPhpName();
        }

        return $getters;
    }

}
