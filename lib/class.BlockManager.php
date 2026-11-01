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
use CMSMS\Database\Connection as Database;

/**
 * A class to aide in managing blocks, including storing and loading from the database.
 *
 */
class BlockManager implements IBlockManagerDecorator
{
    /**
     * @ignore
     */
    private $db;

    /**
     * Constructor
     *
     * @internal
     * @param Database $db The database connection.
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    /**
     * Create a skeleton block object
     */
    public function create_new_block() : ContentBlock
    {
        $data = null;
        $data['name'] = '';
        $data['prompt'] = '';
        $data['type'] = 'textinput';
        $data['value'] = '';
        $data['attribs'] = array();
        $data['attribs']['length'] = '80';
        $data['attribs']['maxlength'] = '255';
        $data['attribs']['rows'] = '8';
        $data['attribs']['cols'] = '50';
        $data['attribs']['wysiwyg'] = true;
        $data['attribs']['options'] = '';
        $data['attribs']['fieldtext'] = '';
        $data['attribs']['storagedelimiter'] = ',';
        $data['attribs']['value'] = 1;
        $data['attribs']['udt'] = null;
        $data['attribs']['adv_start'] = -1;
        $data['attribs']['adv_navhidden'] = 0;
        $data['attribs']['gcb_prefix'] = '';
        $data['attribs']['sortable_maxitems'] = -1;
        return ContentBlock::from_array($data);
    }

    /**
     * Load all known content block definitions.
     *
     * @return ContentBlock[]|null Either an array of content blocks, or null.
     */
    public function load_all()
    {
        $sql = 'SELECT * FROM '.$this->table_name().' ORDER BY name';
        $arr = $this->db->GetArray($sql);
        if( empty($arr) ) return;

        $out = null;
        foreach( $arr as $row ) {
            $out[] = ContentBlock::from_array($row);
        }
        return $out;
    }

    /**
     * Given a name, attempt to load the matching content block.
     *
     * @param string $name
     * @return ContentBlock|null
     */
    public function load_by_name(string $name)
    {
        $name = trim($name);
        if( !$name ) throw new \InvalidArgumentException('Invalid name passed to '.__METHOD__);
        $sql = 'SELECT * FROM '.$this->table_name().' WHERE name = ?';
        $row = $this->db->GetRow($sql, [$name]);
        if( !$row ) return;

        return ContentBlock::from_array($row);
    }

    /**
     * Given an id, attempt to load the matching content block.
     *
     * @param int $id
     * @return ContentBlock|null
     */
    public function load_by_id(int $id)
    {
        if( $id < 1 ) throw new \InvalidArgumentException('Invalid id passed to '.__METHOD__);
        $sql = 'SELECT * FROM '.$this->table_name().' WHERE id = ?';
        $row = $this->db->GetRow($sql, [$id]);
        if( !$row ) return;

        return ContentBlock::from_array($row);
    }

    /**
     * Save the content block to the database.
     * This method will do validation on the input data, and then save it to the database.
     *
     * If the block has an id value greater than 0, then it is assumed to already exist in the database.
     *
     * @param ContentBlock $block
     */
    public function save(ContentBlock $block)
    {
        if( !$block->name ) throw new \InvalidArgumentException('Attempt to save a content block without a name');
        if( !$block->type ) throw new \InvalidArgumentException('Attempt to save a content block without a type');
        if( $block->id < 1 ) {
            // check for existing name
            $sql = 'SELECT id FROM '.$this->table_name().' WHERE name = ?';
            if( (int)$this->db->GetOne($sql,[$block->name]) > 0 ) throw new \LogicException('A block with that name already exists');

            // insert
            $sql = 'INSERT INTO '.$this->table_name().' (type, name, prompt, value, attribs, create_date, modified_date) VALUES (?,?,?,?,?,NOW(),NOW())';
            $this->db->Execute($sql, [ $block->type, $block->name, $block->prompt, $block->value, json_encode($block->attribs) ]);
            $new_id = (int) $this->db->Insert_ID();
        } else {
            // check for existing name
            $sql = 'SELECT id FROM '.$this->table_name().' WHERE name = ? AND id != ?';
            if( (int)$this->db->GetOne($sql,[$block->name, $block->id]) > 0 ) throw new \LogicException('A block with that name already exists');

            // update
            $sql = 'UPDATE '.$this->table_name().' SET type = ?, name = ?, prompt = ?, value = ?, attribs = ?, modified_date = NOW() WHERE id = ?';
            $this->db->Execute($sql, [ $block->type, $block->name, $block->prompt, $block->value, json_encode($block->attribs), $block->id] );
        }
    }

    /**
     * Delete a content block record from the database.
     * this method will not modify the input object,  so before saving it again it should be cloned.
     *
     * @param ContentBlock $block
     */
    public function delete(ContentBlock $block)
    {
        if( $block->id < 1 ) throw new \InvalidArgumentException('A block cannot be deleted that has not yet been saved');
        $this->db->Execute('DELETE FROM '.$this->table_name().' WHERE id = ?', [ $block->id] );
    }

    /**
     * @ignore
     */
    protected function table_name() : string
    {
        return CMS_DB_PREFIX.'module_cgcontentutils';
    }
} // class
