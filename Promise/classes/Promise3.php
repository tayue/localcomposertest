<?php
require_once "PromiseState.php";
// 解决思路：我们肯定要把then传入的回调，放到Promise构造里回调代码执行完后resolve调用后改变了state状态后再调用，所以我们必须存储到一个地方并方便后续调用
// 我们需要改造then、resolve和reject方法
class Promise3
{
    private $value;
    private $reason;
    private $state;
    private $fulfilledCallbacks = [];
    private $rejectedCallbacks = [];

    public function __construct(\Closure $func = null)
    {
        $this->state = PromiseState::PENDING;

        $func([$this, 'resolve'], [$this, 'reject']);
    }

    public function then(\Closure $onFulfilled = null, \Closure $onRejected = null)
    {
        // 如果是异步回调，状态未变化之前，then的回调方法压入相应的数组方便后续调用
        if ($this->state == PromiseState::PENDING) {
            $this->fulfilledCallbacks[] = static function () use ($onFulfilled, $value) {
                $onFulfilled($this->value);
            };

            $this->rejectedCallbacks[] = static function () use ($onRejected, $reason) {
                $onRejected($this->reason);
            };
        }

        // 如果状态是fulfilled，直接回调执行并传参value
        if ($this->state == PromiseState::FULFILLED) {
            $onFulfilled($this->value);
        }

        // 如果状态是rejected，直接回调执行并传参reason
        if ($this->state == PromiseState::REJECTED) {
            $onRejected($this->reason);
        }

        // 返回对象自身，实现链式调用
        return $this;

    }

    /**
     * 执行回调方法里的resolve绑定的方法
     * 本状态只能从pending->fulfilled
     * @param null $value
     */
    public function resolve($value = null)
    {
        if ($this->state == PromiseState::PENDING) {
            $this->state = PromiseState::FULFILLED;
            $this->value = $value;

            array_walk($this->fulfilledCallbacks, function ($callback) {
                $callback();
            });
        }
    }

    /**
     * 执行回调方法里的rejected绑定的方法
     * 本状态只能从pending->rejected
     * @param null $reason
     */
    public function reject($reason = null)
    {
        if ($this->state == PromiseState::PENDING) {
            $this->state = PromiseState::REJECTED;
            $this->reason = $reason;
        }
    }

    public function getState()
    {
        return $this->state;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getReason()
    {
        return $this->reason;
    }
}