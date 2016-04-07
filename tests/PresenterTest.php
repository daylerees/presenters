<?php

class PresenterTest extends PHPUnit_Framework_TestCase
{
    public function test_that_a_presenter_can_be_created()
    {
        return new FirstPresenter;
    }

    public function test_that_a_presenter_can_give_a_payload()
    {
        $p = new SecondPresenter;
        $r = $p->getPresentedPayload();
        $this->assertTrue(is_array($r));
        $this->assertArrayHasKey('foo', $r);
        $this->assertEquals(['foo' => 'bar'], $r);
    }

    public function test_that_a_presenter_can_embed_conditional_values()
    {
        $p = new ThirdPresenter;
        $r = $p->getPresentedPayload();
        $this->assertTrue(is_array($r));
        $this->assertArrayHasKey('foo', $r);
        $this->assertArrayHasKey('bar', $r);
        $this->assertArrayHasKey('baz', $r);
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'boo'
        ], $r);
    }

    public function test_that_a_presenter_can_strip_failed_conditional_values()
    {
        $p = new FourthPresenter;
        $r = $p->getPresentedPayload();
        $this->assertTrue(is_array($r));
        $this->assertArrayHasKey('foo', $r);
        $this->assertArrayNotHasKey('bar', $r);
        $this->assertArrayHasKey('baz', $r);
        $this->assertEquals([
            'foo' => 'bar',
            'baz' => 'boo'
        ], $r);
    }

    public function test_that_a_presenter_can_embed_default_values_on_failure()
    {
        $p = new FifthPresenter;
        $r = $p->getPresentedPayload();
        $this->assertTrue(is_array($r));
        $this->assertArrayHasKey('foo', $r);
        $this->assertArrayHasKey('bar', $r);
        $this->assertArrayHasKey('baz', $r);
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'bink',
            'baz' => 'boo'
        ], $r);
    }
}

/**
 * These are test stubs.
 */

class FirstPresenter extends \Rees\Presenters\Presenter { public function present(){} }

class SecondPresenter extends \Rees\Presenters\Presenter
{
    public function present()
    {
        return ['foo' => 'bar'];
    }
}

class ThirdPresenter extends \Rees\Presenters\Presenter
{
    public function present()
    {
        return [
            'foo' => 'bar',
            'bar' => $this->when(1 == 1)->embed('baz'),
            'baz' => 'boo'
        ];
    }
}

class FourthPresenter extends \Rees\Presenters\Presenter
{
    public function present()
    {
        return [
            'foo' => 'bar',
            'bar' => $this->when(1 == 2)->embed('baz'),
            'baz' => 'boo'
        ];
    }
}

class FifthPresenter extends \Rees\Presenters\Presenter
{
    public function present()
    {
        return [
            'foo' => 'bar',
            'bar' => $this->when(1 == 2)->embed('baz', 'bink'),
            'baz' => 'boo'
        ];
    }
}
