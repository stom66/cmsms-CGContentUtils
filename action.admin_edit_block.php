<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: CGContentUtils (c) 2009 by Robert Campbell
#         (calguy1000@cmsmadesimple.org)
#  An addon module for CMS Made Simple to provide various additional utilities
#  for dealing with content pages.
#
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# This projects homepage is: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE
declare(strict_types = 1);
namespace CGContentUtils;
use cge_param;
if (!isset($gCms)) exit;
$this->SetCurrentTab('blocks');

try {
    //
    // initialization
    //
    $block = $this->blockManager()->create_new_block();

    //
    // setup
    //
    $blockid = cge_param::get_int($params,'blockid');
    if( $blockid > 0 ) $block = $this->blockManager()->load_by_id($blockid);

    //
    // process form data
    //
    if( cge_param::exists($_POST,'cancel') ) {
        $this->RedirectToTab($id);
    }
    else if( cge_param::exists($_POST,'submit') ) {
        try {
            $block = $block->with_name(munge_string_to_url(cge_param::get_string($_POST,'name')));
            $block = $block->with_prompt(cge_param::get_string($_POST,'prompt',$block->name));
            $block = $block->with_type(cge_param::get_string($_POST,'type'));
            $block = $block->with_value(cge_param::get_string($_POST,'dfltvalue'));

            switch( $block->type ) {
            case 'textinput':
                $block = $block->with_attrib('length', cge_param::get_int($_POST,'length'))
                    ->with_attrib('maxlength', cge_param::get_int($_POST,'maxlength'));
                break;

            case 'date':
                $block = $block->with_attrib('min', cge_param::get_string($_POST,'date_min'))
                    ->with_attrib('max', cge_param::get_string($_POST,'date_max'));
                break;

            case 'number':
                $block = $block->with_attrib('min', cge_param::get_string($_POST,'num_min'))
                    ->with_attrib('max', cge_param::get_string($_POST,'num_max'))
                    ->with_attrib('step', cge_param::get_string($_POST,'num_step'));
                break;

            case 'textarea':
                $block = $block->with_attrib('rows', cge_param::get_int($_POST,'rows'))
                    ->with_attrib('cols', cge_param::get_int($_POST,'cols'))
                    ->with_attrib('wysiwyg', cge_param::get_bool($_POST,'wysiwyg'));
                break;

            case 'statictext':
                $block = $block->with_attrib('fieldtext', cge_param::get_string($_POST,'fieldtext'));
                break;

            case 'dropdown':
                $block = $block->with_attrib('options', html_entity_decode(cge_param::get_string($_POST,'options')));
                break;

            case 'dropdown_udt':
                $block = $block->with_attrib('udt', cge_param::get_string($_POST,'udt'));
                break;

            case 'gcb_selector':
                $block = $block->with_attrib('gcb_prefix', cge_param::get_string($_POST,'gcb_prefix'));
                break;

            case 'multiselect':
                $block = $block->with_attrib('options', html_entity_decode(cge_param::get_string($_POST,'multiselect')));
                $block = $block->with_attrib('storagedelimiter', cge_param::get_string($_POST,'storagedelimiter'));
                break;

            case 'sortable_list':
                $block = $block->with_attrib('options', html_entity_decode(cge_param::get_string($_POST,'multiselect')));
                $block = $block->with_attrib('sortable_maxitems', cge_param::get_int($_POST,'stortable_maxitems'));
                break;

            case 'checkbox':
                $block = $block->with_attrib('value', cge_param::get_string($_POST,'value'));
                break;

            case 'radiobuttons':
                $block = $block->with_attrib('options', html_entity_decode(cge_param::get_string($_POST,'multiselect')));
                break;

            case 'file_selector':
                if( $_POST['directory'] == '0' || $_POST['directory'] == '/' ) $_POST['directory'] = '';
                $block = $block->with_attrib('dir', cge_param::get_string($_POST,'directory'));
                $block = $block->with_attrib('excludeprefix', cge_param::get_string($_POST,'excludeprefix'));
                $block = $block->with_attrib('filetypes', cge_param::get_string($_POST,'filetypes'));
                $block = $block->with_attrib('recurse', cge_param::get_bool($_POST,'recurse'));
                $block = $block->with_attrib('sortfiles', cge_param::get_bool($_POST,'sortfiles'));
                break;

            case 'advpageselector':
                $block = $block->with_attrib('adv_start', cge_param::get_int($_POST,'adv_start'));
                $block = $block->with_attrib('adv_navhidden', cge_param::get_int($_POST,'adv_navhidden'));
                break;
            }

            // validation
            if( !$block->name ) throw new \Exception($this->Lang('error_namerequired'));
            if( !$block->prompt ) $block = $block->with_prompt($block->name);

            $data = $block->attribs;
            // validate
            switch( $block->type ) {
            case 'textinput':
                if( $data['length'] < 1 || $data['maxlength'] < 1 ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'number':
                if( $data['min'] && $data['max'] && $data['min'] >= $data['max'] ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'date':
                $a = strtotime($data['min']);
                $b = strtotime($data['max']);
                if( $a && $b && $a >= $b ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'textarea':
                if( $data['rows'] < 1 || $data['cols'] < 1 ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'statictext':
                if( $data['fieldtext'] == '' ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'dropdown':
                if( $data['options'] == '' ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'multiselect':
                if( $data['options'] == '' ) throw new \Exception($this->Lang('error_missing_param'));
                if( $data['storagedelimiter'] == '' ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'checkbox':
                if( $data['value'] == '' ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'radiobuttons':
                if( $data['options'] == '' ) throw new \Exception($this->Lang('error_missing_param'));
                break;

            case 'file_selector':
                // no checking (yet).
                break;
            }

            $this->blockManager()->save($block);
            $this->RedirectToTab();
        }
        catch( \Exception $e ) {
            echo $this->ShowErrors($e->GetMessage());
        }
    }

    //
    // give everything to smarty
    //
    $tpl = $this->CreateSmartyTemplate('admin_edit_block.tpl');
    $dirs = ['/'=>'/'];
    $tmp = glob($config['uploads_path'].'/*',GLOB_ONLYDIR);
    if( is_array($tmp) ) {
        for( $i = 0; $i < count($tmp); $i++ ) {
            $tmps = str_replace($config['uploads_path'].'/','',$tmp[$i]);
            if( startswith($tmps,'_') || startswith($tmps,'.') ) continue;
            $dirs[$tmps] = $tmps;
        }
        $tpl->assign('directories', $dirs);
    }
    $tpl->assign('formstart', $this->CGCreateFormStart($id,'admin_edit_block',$returnid,$params));
    $tpl->assign('formend', $this->CreateFormEnd());
    $blocktypes = null;
    foreach( ContentBlock::types as $one ) {
        $blocktypes[$one] = $this->Lang('blocktype_'.$one);
    }

    $usertags = $this->cms->GetUserTagOperations()->ListUserTags();
    $tpl->assign('usertags',$usertags);

    $tpl->assign('blocktypes',$blocktypes);
    $tpl->assign('one',$block);
    // display the thing
    $tpl->display();
}
catch( \Exception $e ) {
    $this->SetError($e->GetMessage());
    $this->RedirectToTab();
}
#
# EOF
#
