<?php
namespace CGContentUtils;
use cge_utils;
use cge_param;
use \DomDocument;
if( !isset($gCms) ) exit;
if( !$this->CheckPermission('Manage All Content') ) exit;
$this->SetCurrentTab('import');

try {
    if( !empty($_POST) ) {
        $name = 'file';
        if( !isset($_FILES) || !isset($_FILES[$name]) || $_FILES[$name]['error'] > 0 || $_FILES[$name]['size'] == 0 ) {
            throw new \RuntimeException($this->Lang('error_upload'));
        }

        $dflt_template = $this->_getDefaultTemplateId();
        if( !$dflt_template ) throw new \RuntimeException($this->Lang('no_default_dm_template'));

        $doc = new DomDocument();
        $doc->load($_FILES[$name]['tmp_name']);
        $root = $doc->firstChild->firstChild;

        $parent = cge_param::get_int($_POST,'parent_id');
        $n_imported = $this->createImporter($dflt_template)->import($root,$parent);

        $this->SetMessage($this->Lang('msg_imported',$n_imported));
        $this->RedirectToTab();
    }
    $tpl = $this->CreateSmartyTemplate('admin_import_content.tpl');
    $tpl->display();
}
catch( \Exception $e ) {
    cge_utils::log_exception($e);
    $this->SetError($e->GetMessage());
    $this->RedirectToTab();
}
