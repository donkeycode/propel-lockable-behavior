/**
* Activate lock check for modified fields
*/
public function activateLockCheck()
{
    $this->lockedCopy = $this->copy();
    $this->lockCheckActivated = true;
}

public function deactivateLockCheck()
{
    $this->lockedCopy = null;
    $this->lockCheckActivated = false;
}

/**
* Activate locker for modified fields
*/
public function activateLocker()
{
    $this->lockerActivated = true;
}

public function deactivateLocker()
{
    $this->lockerActivated = false;
}

/**
* Auto lock modified fields
*/
protected function lockModifiedFields()
{
    if (!$this->lockerActivated) {
        return;
    }

    <?php foreach ($apply_to as $field) : ?>if ($this->isColumnModified(<?php echo $fields[$field]; ?>)) {
        $this-><?php echo $setters[$field]; ?>Lock(true);
    }

    <?php endforeach; ?>
}

protected function revertLockedFields()
{
    if (!$this->lockCheckActivated) {
        return;
    }

    <?php foreach ($apply_to as $field) : ?>if ($this->isColumnModified(<?php echo $fields[$field]; ?>) && $this-><?php echo $getters[$field]; ?>Lock()) {
        $this-><?php echo $setters[$field]; ?>($this->lockedCopy-><?php echo $getters[$field]; ?>());
    }

    <?php endforeach; ?>
}
