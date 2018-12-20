<ul>
<{foreach item=form from=$block.form}>
  <li><a href="<{$xoops_url}>/modules/tad_form/index.php?op=sign&ofsn=<{$form.ofsn}>"><{$form.title}></a></li>
<{/foreach}>
</ul>