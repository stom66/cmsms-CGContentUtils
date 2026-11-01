<div class="pageoptions">
    <a href="{cms_action_url action=admin_edit_block}">{admin_icon icon='newobject.gif'} {$mod->Lang('add_block')}</a>
</div>

{if !empty($blocks)}
    <table class="pagetable" cellspacing="0">
    	<thead>
	    <tr>
      	        <th>{$mod->Lang('name')}</th>
      		<th>{$mod->Lang('type')}</th>
      		<th>{$mod->Lang('usage')}</th>
      		<th class="pageicon"></th>
      		<th class="pageicon"></th>
    	    </tr>
  	</thead>
  	<tbody>
  	    {foreach $blocks as $one}
	        {cms_action_url action=admin_edit_block blockid=$one->id assign=edit_url}
	        {cms_action_url action=admin_delete_block blockid=$one->id assign=del_url}
  	        <tr class="{cycle values='row1,row1'}">
    		    <td><a href="{$edit_url}" title="{$mod->Lang('edit')}">{$one->name}</a></td>
    		    <td>{capture assign='tmp'}blocktype_{$one->type}{/capture}{$mod->Lang($tmp)}</td>
    		    <td><code>{ldelim}content_module module=CGContentUtils block="{$one->name}"{rdelim}</code></td>
    		    <td><a href="{$edit_url}" title="{$mod->Lang('edit')}">{admin_icon icon='edit.gif'}</a></td>
    		    <td><a href="{$del_url}" title="{$mod->Lang('delete')}">{admin_icon icon='delete.gif'}</a></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{/if}
