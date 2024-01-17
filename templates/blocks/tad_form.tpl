<ul class="vertical_menu">
    <{foreach from=$block item=form}>
        <li><i class="fa fa-list-alt" aria-hidden="true"></i>
        <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$form.ofsn}>"><{$form.title}></a></li>
    <{/foreach}>
</ul>