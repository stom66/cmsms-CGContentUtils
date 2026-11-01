{form_start action=admin_import_content}
<div class="c_full cf">
  <label class="grid_3">{$mod->Lang('parent')}</label>
  <div class="grid_8">
     {page_selector}
  </div>
</div>

<div class="c_full cf">
  <label class="grid_3">{$mod->Lang('file')}</label>
  <input type="file" class="grid_8" name="file"/>
</div>

<div class="c_full cf">
  <input type="submit" value="{$mod->Lang('submit')}"/>
</div>
{form_end}