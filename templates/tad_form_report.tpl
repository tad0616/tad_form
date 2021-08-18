<{$toolbar}>

<link href="<{$xoops_url}>/modules/tad_form/class/ScrollTable/superTables.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<{$xoops_url}>/modules/tad_form/class/ScrollTable/superTables.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tad_form/class/ScrollTable/jquery.superTable.js"></script>

<{if $form_title}>
  <h1><{$form_title}></h1>
<{else}>
  <h2 class="sr-only">report</h2>
<{/if}>
<script>

$(document).ready(function(){
    $("#GridView1").toSuperTable({ width:"98%" , height: "350px", fixedCols: 1 });
});

</script>

<table class="table table-striped " id="GridView1">
  <tr><th <{$thSty}>><{$smarty.const._MD_TADFORM_COL_WHO}></th>
  <{foreach item=tt from=$all_title}>
    <th <{$thSty}>><{$tt.title}></th>
  <{/foreach}>
  <th <{$thSty}>><{$smarty.const._MD_TADFORM_FILL_TIME}></th><{$funct_title}></tr>
  <{foreach item=col from=$result_col}>
  <tr>
    <td style="text-align:center;">
      <a href="<{$col.url}>"><{$col.man_name}></a>
      <div style='font-size: 0.7em'><{$col.fill_time}></div>
    </td>
    <{foreach item=ans from=$col.ans}>
    <td><{$ans.val}></td>
    <{/foreach}>
    <td><{$col.fill_time}></td>
    <td><{$col.other_fun}></td>
  </tr>
  <{/foreach}>
</table>
