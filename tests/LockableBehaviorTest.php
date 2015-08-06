<?php

/**
 * @author Cedric LOMBARDOT <cedric@donkeycode.com>
 */
class LockableBehaviorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Post')) {
            $schema = <<<EOF
<database name="lockable_behavior" defaultIdMethod="native">
    <table name="post">
        <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />

        <column name="title" type="varchar" size="255" />

        <behavior name="lockable">
            <parameter name="apply_to" value="title" />
            <parameter name="with_description" value="true" />
        </behavior>
    </table>
</database>
EOF;
            $builder = new PropelQuickBuilder();
            $config  = $builder->getConfig();
            $config->setBuildProperty('behavior.visibility.class', '../src/LockableBehavior');
            $builder->setConfig($config);
            $builder->setSchema($schema);

            $builder->build();
        }
    }

    public function testObjectMethods()
    {
        $this->assertTrue(method_exists('Post', 'getTitleLock'));
        $this->assertTrue(method_exists('Post', 'setTitleLock'));
    }

    public function testRevertLockedFields()
    {
        $post = new Post();
        $post->activateLocker();
        $post->setTitle('A super book');
        $post->save();
        $this->assertTrue($post->getTitleLock());

        $post->activateLockCheck();
        $post->setTitle('New Title');
        $this->assertEquals('New Title', $post->getTitle());

        $post->save();
        $this->assertEquals('A super book', $post->getTitle());
    }

    public function testLockedFields()
    {
        $post = new Post();
        $post->activateLocker();
        $post->activateLockCheck();

        $post->setTitle('A super book');
        $post->save();
        $this->assertTrue($post->getTitleLock());
        $this->assertEquals('A super book', $post->getTitle());

        $post->setTitle('New Title');
        $this->assertEquals('New Title', $post->getTitle());

        $post->save();
        $this->assertEquals('A super book', $post->getTitle());
    }
}
