<?php
namespace CGContentUtils;
use DomNode;
use ContentOperations;
use CmsLayoutTemplateType;
use CmsLayoutTemplate;

class contentImporter
{
    private $dflt_template_id;
    private $contentops;
    private $template_ops;
    private $owner_id;
    private $template_cache;

    public function __construct(ContentOperations $ops, templateCache $tc, int $template_id, int $owner_id)
    {
        if( $owner_id < 1 ) throw new \InvalidArgumentException('Invalid owner id passed to '.__METHOD__);
        if( $template_id < 1 ) throw new \InvalidArgumentException('Invalid template id passed to '.__METHOD__);
        $this->dflt_template_id = $template_id;
        $this->template_ops = $tc;
        $this->contentops = $ops;
        $this->owner_id = $owner_id;
    }

    protected function get_node_value(DomNode $parent,string $nodename)
    {
        $children = $parent->childNodes;
        foreach( $children as $childnode ) {
            if( $childnode->nodeName == $nodename ) return $childnode->nodeValue;
        }
        return NULL;
    }


    protected function get_node_attribute(DomNode $node,string $attrname)
    {
        $attr = $node->attributes->getNamedItem($attrname);
        if( $attr ) return $attr->nodeValue;
        return NULL;
    }

    protected function extractXmlContentObj(DomNode $node)
    {
        $_to_int = function($in) {
            return (int)trim((string)$in);
        };

        // a. get the content type
        $contenttype = $this->get_node_value($node,'type');
        if( !$contenttype ) return;

        // b. create the new content object for filling.
        $content_obj = $this->contentops->CreateNewContent($contenttype);
        if( !$content_obj ) return;

        $content_obj->SetName($this->get_node_attribute($node,'name'));
        $content_obj->SetMenuText($this->get_node_value($node,'menutext'));
        $content_obj->SetActive($this->get_node_value($node,'active'));
        $content_obj->SetAccessKey($this->get_node_value($node,'accesskey'));
        $content_obj->SetTabIndex($_to_int($this->get_node_value($node,'tabindex')));
        $content_obj->SetMetaData($this->get_node_value($node,'metadata'));
        $content_obj->SetCachable($this->get_node_value($node,'cachable'));
        $content_obj->SetShowInMenu($this->get_node_value($node,'showinmenu'));

        $alias = $this->get_node_value($node,'alias');
        $tmp = $this->contentops->CheckAliasError($alias);
        if( $tmp ) $alias = '';
        $content_obj->SetAlias($alias);

        // todo, handle null.
        $tpl_id = $this->template_ops->getTemplateIdFromName($this->get_node_value($node,'template'));
        if( !$tpl_id ) $tpl_id = $this->dflt_template_id;
        if( !$tpl_id ) throw new \LogicException('Could not find a template id to assign to the page');
        $content_obj->SetTemplateId($tpl_id);

        // now to get the properties.
        $children = $node->childNodes;
        foreach( $children as $childnode ) {
            if( $childnode->nodeName != 'property' ) continue;
            $propname = $this->get_node_attribute($childnode,'name');
            $proptype = $this->get_node_attribute($childnode,'type');
            $propval  = $childnode->nodeValue;
            $content_obj->setPropertyValue($propname,$propval);
        }

        return $content_obj;
    }

    public function import(DomNode $node, int $dest_parent_id) : int
    {
        $imported = 0;
        while( $node != NULL ) {

            // this is the top level import stuff.
            if( $node->nodeName == 'cms_content' ) {
                $content_obj = $this->extractXmlContentObj($node);
                if( $content_obj ) {
                    $content_obj->SetParentId($dest_parent_id);
                    $content_obj->SetOwner($this->owner_id);
                    if( version_compare(CMS_VERSION,'2.2.99') < 1) {
                        $content_obj->save();
                    }
                    else {
                        $content_obj = $this->contentops->save_content($content_obj);
                    }
                    $new_parent_id = $content_obj->Id();
                    $imported++;
                    // recursion for nested content pages
                    $imported += $this->import($node->firstChild, $new_parent_id);
                }
            }
            $node = $node->nextSibling;

        }
        return $imported;
    }
} // class