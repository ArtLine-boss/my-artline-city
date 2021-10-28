<?php
class core_Cache extends core_Singleton
{
    private $cacheEntity = [];
    /**
     * @param core_DBObject $object
     */
    public function set($object) {
        if(empty($this->cacheEntity) || !is_array($this->cacheEntity)) {
            $this->cacheEntity = [];
        }
        $this->cacheEntity[get_class($object)][$object->getId()] = $object;
    }

    /**
     * @param string $class_name
     * @param string|int|null $id
     * @return core_DBObject|null
     */
    public function get($class_name, $id) {
        if(empty($class_name) || empty($id)) {
            return null;
        }
        return isset($this->cacheEntity[$class_name][$id]) ? $this->cacheEntity[$class_name][$id] : null;
    }

    /**
     * @return array
     */
    public function getCacheEntity() {
        return $this->cacheEntity;
    }
}