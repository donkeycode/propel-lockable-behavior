<?php

/**
 * @author Cedric LOMBARDOT <cedric@donkeycode.com>
 */
class LockableBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $parameters = array(
        'apply_to'            => array(),
    );

    /**
     * @var array
     */
    protected $applyToFields;

    /**
     * @var LockableBehaviorObjectBuilderModifier
     */
    protected $objectBuilderModifier;

    /**
     * {@inheritdoc}
     */
    public function modifyTable()
    {
        foreach ($this->getApplyToFields() as $field) {
            if (!$this->getTable()->containsColumn($field.'_lock')) {
                $column = array(
                    'name'          => $field.'_lock',
                    'type'          => 'BOOLEAN',
                    'defaultValue'  => false,
                );

                if ('true' === $this->getParameter('with_description')) {
                    $column['description'] = $this->generateLockableColumnComment();
                }

                $this->getTable()->addColumn($column);
            }
        }
    }

    /**
     * Generate a column comment
     *
     * @return string
     */
    protected function generateLockableColumnComment()
    {
        return 'true if data is locked';
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectBuilderModifier()
    {
        if (null === $this->objectBuilderModifier) {
            $this->objectBuilderModifier = new LockableObjectBuilderModifier($this);
        }

        return $this->objectBuilderModifier;
    }

    public function getApplyToFields()
    {
        if (null === $this->applyToFields) {
            $fields = array();
            foreach (explode(',', $this->getParameter('apply_to')) as $field) {
                $fields[] = strtolower(trim($field));
            }

            $this->applyToFields = $fields;
        }

        return $this->applyToFields;
    }

    public function camelize($string)
    {
        return ucfirst(str_replace(' ', '', ucwords(strtr($string, '_-', '  '))));
    }
}
