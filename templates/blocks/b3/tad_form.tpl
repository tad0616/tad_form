<ul class="vertical_menu">
    <{foreach item=form from=$block.form}>
        <li><i class="fa fa-list-alt" aria-hidden="true"></i>
        <a href="<{$xoops_url}>/modules/tad_form/index.php?op=sign&ofsn=<{$form.ofsn}>"><{$form.title}></a></li>
    <{/foreach}>
</ul>