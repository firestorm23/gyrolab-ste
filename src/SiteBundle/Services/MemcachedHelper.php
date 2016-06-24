<?php

namespace SiteBundle\Services;
use Doctrine\Common\Cache\MemcachedCache;

class MemcachedHelper {

    private $memcached;

    public function setCache($cache_key, $cache_var, $ttl = 86400) {
        if (!$this->checkMd5($cache_key)) {
            $cache_key = md5($cache_key);
        }
        if (!is_int($ttl)) {
            $ttl = 86400;
        }
        $serialized_var = $this->serialize($cache_var);
        return $this->save($cache_key, $serialized_var, $ttl);
    }

    public function getMemcached() {
//        if (empty($this->memcached)) {
//            $this->memcached = new MemcachedCache();
//            $instance = new \Memcached();
//            $instance->addServer('localhost', 11211);
//            $this->memcached->setMemcached($instance);
//        }
        return $this->memcached;
    }

    public function setMemcached(MemcachedCache $memcachedCache) {
        $this->memcached = $memcachedCache;
    }

    public function getAllKeys() {
        return $this->memcached->getMemcached()->getAllKeys();
    }


    private function save($cache_key, $serialized_var, $ttl) {
        try {
            $result = $this->getMemcached()->save($cache_key, $serialized_var, $ttl);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Error while serializing var " . " in " . __FILE__ . " : " . __LINE__.". System message: \n".$e->getMessage());
        }
    }

    public function getCache($cache_key) {
        if (!$this->checkMd5($cache_key)) {
            $cache_key = md5($cache_key);
        }
        try {
            $cached_serialized_result = $this->getMemcached()->fetch($cache_key);
            //fileDump(array($cached_serialized_result), true);
            $result = $this->unserialize($cached_serialized_result);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Error while serializing var " . " in " . __FILE__ . " : " . __LINE__.". System message: \n".$e->getMessage());
        }
    }

    private function checkMd5($key) {
        return is_string($key) && strlen($key) == 32 && ctype_xdigit($key);
    }

    private function serialize($var) {
        try {
            $result = serialize($var);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Error while serializing var " . " in " . __FILE__ . " : " . __LINE__.". System message: \n".$e->getMessage());
        }
    }
    private function unserialize($var) {
        try {
            $result = unserialize($var);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Error while unserializing cache result " . " in " . __FILE__ . " : " . __LINE__.". System message: \n".$e->getMessage());
        }
    }

}
?>