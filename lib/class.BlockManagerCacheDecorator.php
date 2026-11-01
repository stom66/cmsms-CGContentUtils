<?php

/**
 * A class to store content blocks in the database.
 *
 * @package CGContentUtils
 * @author Robert Campbell <calguy1000@gmail.com>
 * @license GPL
 */

declare(strict_types = 1);
namespace CGContentUtils;
use cms_cache_driver as cache_driver;

/**
 * This is a decorator class that implements caching
 */
class BlockManagerCacheDecorator implements IBlockManagerDecorator
{
    private $parent;
    private $driver;

    public function __construct(IBlockManagerDecorator $parent, cache_driver $driver)
    {
        $this->parent = $parent;
        $this->driver = $driver;
    }

    public function create_new_block() : ContentBlock
    {
        return $this->parent->create_new_block();
    }

    public function load_all()
    {
        if( ($data = $this->driver->get(__METHOD__,__CLASS__)) ) {
            return $data;
        }
        $data = $this->parent->load_all();
        $this->driver->set(__METHOD__, $data, __CLASS__);
        return $data;
    }

    public function load_by_name(string $name)
    {
        if( ($data = $this->driver->get($name,__CLASS__)) ) {
            return $data;
        }
        $data = $this->parent->load_by_name($name);
        $this->driver->set($name, $data, __CLASS__);
        return $data;
    }

    public function load_by_id(int $id)
    {
        if( ($data = $this->driver->get($id,__CLASS__)) ) {
            return $data;
        }
        $data = $this->parent->load_by_id($id);
        $this->driver->set($id, $data, __CLASS__);
        return $data;
    }

    public function save(ContentBlock $block)
    {
        $this->driver->clear(__CLASS__);
        return $this->parent->save($block);
    }

    public function delete(ContentBlock $block)
    {
        $this->driver->clear(__CLASS__);
        return $this->parent->delete($block);
    }
} // class