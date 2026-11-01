{form_start action=admin_export_content}
<div class="c_full cf">
  <label class="grid_3">{$mod->Lang('start_page')}:</label>
  <div class="grid_8">{page_selector}</div>
</div>

<div class="c_full cf">
  <label class="grid_3">{$mod->Lang('export_children')}:</label>
  <select class="grid_2" name="children">
     {cge_yesno_options}
  </select>
</div>

<div class="c_full cf">
  <input type="submit" value="{$mod->Lang('submit')}"/>
</div>
{form_end}