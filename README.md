# LockableBehavior

[![Build Status](https://secure.travis-ci.org/donkeycode/LockableBehavior.png)](http://travis-ci.org/cedriclombardot/LockableBehavior)

The **LockableBehavior** behavior allows you to mark columns edited and choose forceUpdate or not.


Installation
------------

Cherry-pick the `LockableBehavior.php` file is `src/`, put it somewhere,
then add the following line to your `propel.ini` or `build.properties` configuration file:

``` ini
propel.behavior.visibility.class = path.to.LockableBehavior
```


Usage
-----

Just add the following XML tag in your `schema.xml` file:

``` xml
<behavior name="lockable">
    <!-- Choose columns to apply -->
    <parameter name="apply_to" value="my_field, my_other_field" />
</behavior>
```

The **lockable** behavior requires four parameters to work:

* `apply_to`: the list of column to apply the visibility behavior


## In php :

* `$post->activateLocker()` set true to locker fields at `preSave`
* `$post->activateLockCheck()` revert locked fields at `preSave` to only persist not locked fields

