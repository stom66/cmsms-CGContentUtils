<?php
namespace CGContentUtils;
use CmsLayoutTemplate;
use CmsLayoutTemplateType;

class templateCache
{
    private $template_cache;

    public function getTemplateNameFromId(int $tplid)
    {
        if( !$this->template_cache ) {
            $tpl_type = CmsLayoutTemplateType::load('Core::Page');
            $parms = [];
            $parms[] = 't:'.$tpl_type->get_id();
            $parms['as_list'] = 1;
            $list = CmsLayoutTemplate::template_query($parms);
            $this->template_cache = [];
            if( !empty($list) ) $this->template_cache = $list;
        }
        if( isset($this->template_cache[$tplid]) ) return $this->template_cache[$tplid];
    }

    public function getTemplateIdFromName(string $name)
    {
        // force cache to load.
        $this->getTemplateNameFromId(-1);
        foreach( $this->template_cache as $id => $tpl_name ) {
            if( $tpl_name == $name ) return $id;
        }
        return;
    }

} // class