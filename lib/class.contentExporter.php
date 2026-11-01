<?php
namespace CGContentUtils;
use ContentBase;
use DomDocument;
use DomNode;
use cms_content_tree;
use ContentOperations;
use CmsLayoutTemplateType;
use CmsLayoutTemplate;

class contentExporter
{
    private $hm;
    private $template_ops;
    private $_dom;

    public function __construct(cms_content_tree $hm, templateCache $tc)
    {
        $this->hm = $hm;
        $this->template_ops = $tc;
    }

    function createDomContentObj(ContentBase $content_obj)
    {
        $root = $this->_dom->createElement('cms_content');
        $root->setAttribute('name',$content_obj->Name());

        $sub = $this->_dom->createElement('type',$content_obj->Type());
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('alias',$content_obj->Alias());
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('template',$this->template_ops->getTemplateNameFromId($content_obj->TemplateID()));
        $root->appendChild($sub);

        $cdata = $this->_dom->createCDATAsection($content_obj->MetaData());
        $sub = $this->_dom->createElement('metadata');
        $sub->appendChild($cdata);
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('accesskey',$content_obj->AccessKey());
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('tabindex',$content_obj->TabIndex());
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('menutext',$content_obj->MenuText());
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('active',$content_obj->Active());
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('cachable',$content_obj->Cachable());
        $root->appendChild($sub);

        $sub = $this->_dom->createElement('showinmenu',$content_obj->ShowInMenu());
        $root->appendChild($sub);

        $content_obj->HasProperty('___'); // fools properties to be loaded.
        $props = $content_obj->Properties();
        if( is_array($props) && count($props) ) {
            foreach( $props as $name => $val ) {
                $sub = $this->_dom->createElement('property');
                $sub->setAttribute('name',$name);
                $sub->setAttribute('type','string');
                $cdata = $this->_dom->createCDATAsection($val);
                $sub->appendChild($cdata);
                $root->appendChild($sub);
            }
        }

        return $root;
    }

    protected function exportContentObj(cms_content_tree $node,bool $children)
    {
        $content_obj = $node->getContent(FALSE,TRUE,TRUE);
        if( !is_object($content_obj) ) return;
        $domnode = $this->createDomContentObj($content_obj);

        if( $node->hasChildren() && $children ) {
            $tmp = $node->getChildren();
            foreach( $tmp as $child ) {
                $child_domnode = $this->exportContentObj($child,$children);
                if( $child_domnode ) $domnode->appendChild($child_domnode);
            }
        }

        return $domnode;
    }

    public function export(int $start_id, bool $with_children)
    {
        $doc = $this->_dom = new DOMDocument("1.0","UTF-8");
        $root = $doc->createElement('cms_export');
        if( $start_id < 1 ) {
            $allchildren = $this->hm->getChildren(TRUE,TRUE);
            foreach( $allchildren as $child ) {
                if( !$child ) continue;
                $dom_node = $this->exportContentObj($child,$with_children);
                if( $dom_node ) $root->appendChild($dom_node);
            }
        }
        else {
            $node = $this->hm->sureGetNodeByID($start_id);
            if( !$node ) throw new \LogicException('Could not get node with id '.$start_id);

            $dom_node = $this->exportContentObj($node,$with_children);
            if( $dom_node ) $root->appendChild($dom_node);
        }
        $doc->appendChild($root);
        return $doc->saveXML();
    }
} // class