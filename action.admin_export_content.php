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
        $parent_id = cge_param::get_int($_POST,'parent_id');
        $with_children = cge_param::get_bool($_POST,'children');

        $xmltext = $this->createExporter()->export($parent_id, $with_children);
        cge_utils::send_data_and_exit($xmltext, 'text/xml', 'cms_content_export.xml');
    }
    $tpl = $this->CreateSmartyTemplate('admin_export_content.tpl');
    $tpl->display();
}
catch( \Exception $e ) {
    cge_utils::log_exception($e);
    $this->SetError($e->GetMessage());
    $this->RedirectToTab();
}
