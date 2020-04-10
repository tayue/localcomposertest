<?php
require_once "PromiseState.php";
// 尝试一
class Promise1
{
    private $value;
    private $reason;
    private $state;

    public function __construct(\Closure $func = null)
    {
        $this->state = PromiseState::PENDING;

        $func([$this, 'resolve'], [$this, 'reject']);
    }

    /**
     * 执行回调方法里的resolve绑定的方法
     * @param null $value
     */
    public function resolve($value = null)
    {
        // 回调执行resolve传参的值，赋值给result
        $flag=true;
        $this->value = $value;
        if($this->value=="faild"){
            $flag=false;
            return $flag;
        }
        if ($this->state == PromiseState::PENDING) {
            $this->state = PromiseState::FULFILLED;
        }
    }

    public function reject($reason = null)
    {
        // 回调执行resolve传参的值，赋值给result
        $this->reason = $reason;

        if ($this->state == PromiseState::PENDING) {
            $this->state = PromiseState::REJECTED;
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