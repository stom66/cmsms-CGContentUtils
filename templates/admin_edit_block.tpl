{literal}
<script type="text/javascript">
$(function(){
    $('#blocktype').change(function(){
        $('.blocktypes').hide()
        $('#'+$(this).val()).show()
    });
    $('#blocktype').trigger('change')
})
</script>
{/literal}

<style type="text/css" scoped>
textarea.prompt {
   max-height: 3em;
   max-width:  50em;
}
</style>

{if $one->id > 0}
    <h3>{$mod->Lang('title_edit_block')}</h3>
{else}
    <h3>{$mod->Lang('title_add_block')}</h3>
{/if}

{form_start blockid=$one->id}
<div class="c_full cf">
  <label class="grid_3">*{$mod->Lang('name')}:</label>
  <div class="grid_8">
    <input class="grid_12" type="text" name="name" value="{$one->name}" size="40" placeholder="{$mod->Lang('ph_name')}"/>
    <br/>{$mod->Lang('info_blockname')}
  </div>
</div>

<div class="c_full cf">
  <label class="grid_3">{$mod->Lang('prompt')}:</label>
  <div class="grid_8">
    <textarea class="grid_12" rows="3" cols="60" name="prompt" placeholder="{$mod->Lang('ph_prompt')}">{$one->prompt}</textarea>
    <br/>{$mod->Lang('info_blockprompt')}
  </div>
</div>

<div class="c_full cf">
  <label class="grid_3">*{$mod->Lang('type')}:</label>
  <div class="grid_8">
    <select class="grid_12" id="blocktype" name="type">
      {html_options options=$blocktypes selected=$one->type}
    </select>
  </div>
</div>

<div id="default_value" class="c_full cf">
  <label class="grid_3">{$mod->Lang('default_value')}:</label>
  <div class="grid_8">
    <input class="grid_12" type="text" name="dfltvalue" value="{$one->value}"/>
  </div>
</div>

<div class="blocktypes" id="textinput">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_length')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="length" size="3" maxlength="3" value="{$one->attribs.length}" />
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_maxlength')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="maxlength" size="3" maxlength="3" value="{$one->attribs.maxlength}" />
    </div>
  </div>
</div>

<div class="blocktypes" id="date">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_min')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="date" name="date_min" maxlength="20" value="{$one->attribs.min|default:null}" />
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_max')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="date" name="date_max" maxlength="20" value="{$one->attribs.max|default:null}" />
    </div>
  </div>
</div>

<div class="blocktypes" id="number">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_min')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="num_min" maxlength="20" value="{$one->attribs.min|default:null}" />
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_max')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="num_max" maxlength="20" value="{$one->attribs.max|default:null}" />
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_step')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="num_step" maxlength="20" value="{$one->attribs.step|default:null}" />
    </div>
  </div>
</div>


<div class="blocktypes" id="advpageselector">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_adv_start')}:</label>
    <div class="grid_8">
      <select class="grid_12" name="adv_start">
        {cge_pageoptions none=true selected=$one->attribs.adv_start}
      </select>
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_adv_navhidden')}:</label>
    <div class="grid_8">
      <select class="grid_12" name="adv_navhidden">
        {cge_yesno_options selected=$one->attribs.adv_navhidden}
      </select>
    </div>
  </div>
</div>

<div class="blocktypes" id="textarea">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_rows')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="rows" size="3" maxlength="3" value="{$one->attribs.rows}" />
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_cols')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="cols" size="3" maxlength="3" value="{$one->attribs.cols}" />
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_wysiwyg')}:</label>
    <div class="grid_8">
      {cge_yesno_options prefix=$actionid name=wysiwyg selected=$one->attribs.wysiwyg|default:0}
    </div>
  </div>
</div>

<div class="blocktypes" id="statictext">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_text')}:</label>
    <div class="grid_8">
      <textarea class="grid_12" name="fieldtext">{$one->attribs.fieldtext|default:''}</textarea>
      <br/>
      {$mod->Lang('info_statictext')}
    </div>
  </div>
</div>

<div class="blocktypes" id="dropdown">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_options')}:</label>
    <div class="grid_8">
      <textarea class="grid_12" name="options" cols="50" rows="5">{$one->attribs.options}</textarea>
      <br/>
      {$mod->Lang('info_dropdown_options')}
    </div>
  </div>
</div>

<div class="blocktypes" id="sortable_list">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_options')}:</label>
    <div class="grid_8">
      <textarea class="grid_12" name="sortable_list" cols="50" rows="5">{$one->attribs.options}</textarea>
      <br/>
      {$mod->Lang('info_dropdown_options')}
    </div>
  </div>
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_sortable_maxitems')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="sortable_maxitems" size="3" value="{$one->attribs.sortable_maxitems|default:''}"/>
      <br/>
      {$mod->Lang('info_sortable_maxitems')}
    </div>
  </div>

</div>

<div class="blocktypes" id="dropdown_udt">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_udt')}:</label>
    <div class="grid_8">
      <select class="grid_12" name="dropdown_udt">
        {html_options options=$usertags selected=$one->attribs.udt}
      </select>
      <br/>{$mod->Lang('info_seludt')}
    </div>
  </div>
</div>

<div class="blocktypes" id="multiselect">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_options')}:</label>
    <div class="grid_8">
      <textarea class="grid_12" name="multiselect" cols="50" rows="5">{$one->attribs.options}</textarea>
      <br/>{$mod->Lang('info_dropdown_options')}
    </div>
  </div>
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_storagedelimiter')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="storagedelimiter" size="5" value="{$one->attribs.storagedelimiter|default:''}"/>
      <br/>{$mod->Lang('info_storagedelimiter')}
    </div>
  </div>
</div>

<div class="blocktypes" id="checkbox">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_value')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="value" size="80" maxlength="255" value="{$one->attribs.value}" />
    </div>
  </div>
</div>

<div class="blocktypes" id="radiobuttons">
  <div class="c_full cf">
    <label class="grid_3">*{$mod->Lang('prompt_options')}:</label>
    <div class="grid_8">
      <textarea class="grid_12" name="radiooptions">{$one->attribs.options}</textarea>
      <br/>{$mod->Lang('info_dropdown_options')}
    </div>
  </div>
</div>

<div class="blocktypes" id="gcb_selector">
  <div class="c_full cf">
    <label class="grid_3">{$mod->Lang('prompt_gcb_prefix')}:</label>
    <div class="grid_8">
      <input class="grid_12" name="gcb_prefix" size="20" maxlength="20" value="{$one->attribs.gcb_prefix|default:''}"/>
      <br/>{$mod->Lang('info_gcb_prefix')}
    </div>
  </div>
</div>

<div class="blocktypes" id="file_selector">
  <div class="c_full cf">
    <label class="grid_3">{$mod->Lang('prompt_dir')}:</label>
    <div class="grid_8">
      <select class="grid_12" name="directory">
      {html_options options=$directories selected=$one->attribs.dir|default:''}
      </select>
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">{$mod->Lang('prompt_filetypes')}:</label>
    <div class="grid_8">
      <input class="grid_12" name="filetypes" size="20" maxlength="255" value="{$one->attribs.filetypes|default:''}"/>
      <br/>{$mod->Lang('info_filetypes')}
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">{$mod->Lang('prompt_excludeprefix')}:</label>
    <div class="grid_8">
      <input class="grid_12" type="text" name="excludeprefix" size="20" maxlength="255" value="{$one->attribs.excludeprefix|default:''}"/>
      <br/>{$mod->Lang('info_excludeprefix')}
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">{$mod->Lang('prompt_recurse')}:</label>
    <div class="grid_8">
      <select class="grid_12" name="recurse">
          {cge_yesno_options selected=$one->attribs.recurse|default:0}
      </select>
    </div>
  </div>

  <div class="c_full cf">
    <label class="grid_3">{$mod->Lang('prompt_sortfiles')}:</label>
    <div class="grid_8">
      <select class="grid_12" name="sortfiles">
          {cge_yesno_options selected=$one->attribs.sortfiles|default:0}
      </select>
    </div>
  </div>
</div>

<div class="c_full cf">
    <button type="submit" name="submit">{$mod->Lang('submit')}</button>
    <button type="submit" name="cancel" formnovalidate>{$mod->Lang('cancel')}</button>
</div>
{form_end}