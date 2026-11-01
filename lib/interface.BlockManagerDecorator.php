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

interface BlockManagerDecorator
{
    public function create_new_block() : ContentBlock;
    public function load_all();
    public function load_by_name(string $name);
    public function load_by_id(int $id);
    public function save(ContentBlock $block);
    public function delete(ContentBlock $block);

} // class