<{$toolbar}>
<link href="<{$xoops_url}>/modules/tad_form/class/ScrollTable/superTables.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<{$xoops_url}>/modules/tad_form/class/ScrollTable/superTables.js"></script>
<script type="text/javascript" src="<{$xoops_url}>/modules/tad_form/class/ScrollTable/jquery.superTable.js"></script>

<{if $form_title}>
  <h1><{$form_title}></h1>
<{else}>
  <h2 class="sr-only visually-hidden">report</h2>
<{/if}>

<script>
    $(document).ready(function(){
        $("#result").toSuperTable({ width:"98%" , height: "350px", fixedCols: 1 });
    });
    function delete_tad_form_ans(ssn){
        var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
        if (!sure)	return;
        location.href="<{$xoops_url}>/modules/tad_form/result.php?op=delete_tad_form_ans&ofsn=<{$ofsn}>&ssn=" + ssn;
    }
</script>

<form action="<{$xoops_url}>/modules/tad_form/result.php" method="post" id="myForm" enctype="multipart/form-data">
    <table class="table table-striped " id="result">
        <tr>
            <th><{$smarty.const._MD_TADFORM_COL_WHO}></th>
            <{foreach item=tt from=$all_title}>
                <th><{$tt.title}></th>
            <{/foreach}>
            <th><{$smarty.const._MD_TADFORM_FILL_TIME}></th><{$funct_title}></tr>
            <{foreach item=col from=$result_col}>
                <tr><td><a href="<{$col.url}>"><{$col.man_name}></a></td>
            <{foreach item=ans from=$col.ans}>
                <td><{$ans.val}></td>
            <{/foreach}>
            <td><{$col.fill_time}></td>
            <{$col.funct}>
        </tr>
        <{/foreach}>
    </table>
    <{$submit}>
</form>

<{if $view_ssn}>

    <h3>[<{$uid}>]<{$man_name}> (<{$email}>) @<{$fill_time}></h3>
    <table <{$tbl_set}>>
    <{foreach item=q from=$all}>
        <tr>
            <td <{$q.td_set}>><{$q.i}>. <b><{$q.title}></b></td>
            <td><{$q.val}></td>
        </tr>
    <{/foreach}>
    </table>
<{else}>

    <h1><{$smarty.const._MD_TADFORM_ANALYSIS}></h1>
    <table class="table table-striped">
        <tr>
            <th><{$smarty.const._MD_TADFORM_COL_TITLE}></th>
            <th><{$smarty.const._MD_TADFORM_COL_FUNC}></th>
            <th><{$smarty.const._MD_TADFORM_ANALYSIS_RESULT}></th>
        </tr>
        <{foreach item=analysis from=$analysis}>
            <tr>
                <td><{$analysis.title}></td>
                <td><{$analysis.func}></td>
                <td><{$analysis.val}></td>
            </tr>
        <{/foreach}>
    </table>

<{/if}>