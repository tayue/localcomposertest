<?php

namespace ServerFramework\Traits;

use ServerFramework\Server\ServerManager;
use Swoole\Coroutine as SwCoroutine;

trait AppTrait
{
    public static $app;

    /**
     * getApp
     * @param int|null $coroutine_id
     * @return $object
     */
    public static function getApp($coroutine_id = null)
    {
        if ($coroutine_id) {
            $cid = $coroutine_id;
        } else {
            $cid = ServerManager::getInstance()->coroutine_id;
        }
        if (!$cid) {
            $cid = SwCoroutine::getCid();
        }
        if (isset(self::$app[$cid])) {
            return self::$app[$cid];
        } else {
            return self::$app;
        }
    }

    /**
     * removeApp
     * @param int|null $coroutine_id
     * @return boolean
     */
    public static function removeApp($coroutine_id = null)
    {
        $cid = SwCoroutine::getCid();
        if ($coroutine_id) {
            $cid = $coroutine_id;
        }
        if (isset(self::$app[$cid])) {
            unset(self::$app[$cid]);
            return true;
        } else {
            self::$app = NULL;
            return false;
        }
        return false;
    }

    /**销毁协程应用
     * @param null $coroutine_id
     * @return bool
     */
    public static function destroy($coroutine_id = null)
    {
        if ($coroutine_id) {
            $cid = $coroutine_id;
        } else {
            $cid = ServerManager::getInstance()->coroutine_id;
        }
        if (!$cid) {
            $cid = SwCoroutine::getCid();
        }
        return self::removeApp($cid);
    }

}
