<{$bootstrap_table|default:''}>

<{if $form.title|default:false}>
    <h2><{$form.title}></h2>
<{else}>
    <h2 class="sr-only visually-hidden">report</h2>
<{/if}>
<div class="my-3">
    <{if $smarty.session.tad_form_manager|default:false}>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_main_create&ofsn=<{$smarty.get.ofsn|intval}>" class="btn btn-sm btn-warning"><i class="fa fa-pen-to-square" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_EDIT}></a>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_index&ofsn=<{$smarty.get.ofsn|intval}>" class="btn btn-sm btn-info"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_EDIT_ALL}></a>
    <{/if}>
    <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$smarty.get.ofsn|intval}>&ssn=<{$smarty.get.ssn|intval}>&code=<{$smarty.get.code|default:''}>" class="btn btn-sm btn-success"><i class="fa fa-undo" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_BACK_TO_FORM}></a>
</div>



<table id="result-table" class="table" data-toggle="table" data-pagination="true" data-search="true" data-mobile-responsive="true" data-fixed-columns="true" data-fixed-number="<{if $form.kind == "application"}>2<{else}>1<{/if}>">
    <thead>
        <tr>
            <th><{$smarty.const._MD_TAD_FORM_COL_WHO}></th>
            <{if $form.kind == "application"}>
                <th><{$smarty.const._MD_TAD_FORM_KIND1_OK}></th>
            <{/if}>
            <{foreach from=$form.col key=csn item=col}>
                <{if $col.public || $smarty.session.tad_form_manager}>
                    <th style="max-width:20rem; overflow: hidden;"><{$col.title}></th>
                <{/if}>
            <{/foreach}>
        </tr>
    </thead>
    <{foreach from=$all_tad_form_fill key=ssn item=fill}>
        <tr>
            <td style="text-align:center;">
                <{if $smarty.session.tad_form_manager|default:false}>
                    <a href="index.php?op=tad_form_fill_show&ofsn=<{$fill.ofsn}>&code=<{$fill.code}>" target="_blank"><{$fill.man_name}></a>
                <{else}>
                    <{$fill.man_name}>
                <{/if}>
                <div style='font-size: 0.8rem'><{$fill.fill_time}></div>
            </td>
            <{if $form.kind == "application"}>
                <td class="text-center">
                    <input type="checkbox" name="result_col[<{$ssn|default:''}>]" data-ssn="<{$ssn|default:''}>" value='1' <{if $fill.result_col == 1}>checked<{/if}>>
                </td>
            <{/if}>
            <{foreach from=$fill.form.col key=csn item=col}>
                <{if $col.kind!="" && ($col.public || $smarty.session.tad_form_manager)}>
                    <td style="max-width:20rem; white-space: unset;">
                    <{if $col.kind=="checkbox"}>
                        <{$fill.ans.$csn|replace:';':'<br>'}>
                    <{elseif $col.kind=="textarea"}>
                        <{$fill.ans.$csn|nl2br}>
                    <{else}>
                        <{$fill.ans.$csn}>
                    <{/if}>
                    </td>
                <{/if}>
            <{/foreach}>
        </tr>
    <{/foreach}>
</table>


<{if $analysis|default:false}>
    <h2><{$smarty.const._MD_TAD_FORM_ANALYSIS}></h2>
    <table class="table table-striped">
        <tr>
            <th><{$smarty.const._MD_TAD_FORM_COL_TITLE}></th>
            <th><{$smarty.const._MD_TAD_FORM_COL_FUNC}></th>
            <th><{$smarty.const._MD_TAD_FORM_ANALYSIS_RESULT}></th>
        </tr>
        <{foreach from=$analysis item=data}>
            <{if $data.func|default:false}>
                <tr>
                    <td><{$data.title}></td>
                    <td><{$data.func}></td>
                    <td>
                    <{if $data.func=="count"}>
                        <{foreach from=$data.val key=option item=val}>
                            <li><{$option|default:''}> = <{$val|default:''}></li>
                        <{/foreach}>
                    <{else}>
                        <{$data.val}>
                    <{/if}>
                    </td>
                </tr>
            <{/if}>
        <{/foreach}>
    </table>
<{/if}>

<{if $form.kind == "application" && $smarty.session.tad_form_manager}>
    <div id="update-message" style="display: none; position: absolute; padding: 10px; background-color: rgba(51, 111, 214, 0.7); color: white; border-radius: 5px;z-index:1000;">更新成功</div>
    <script>
        $(document).ready(function() {
        // 監聽表格內容重新載入的事件
        $('#result-table').on('post-body.bs.table', function () {
            // 綁定 checkbox 的 change 事件
            $('input[name^="result_col"]').off('change').on('change', function() {
                var checkbox = $(this);
                var ssn = checkbox.data('ssn');
                var admitted = this.checked ? 1 : 0;
                console.log('ssn',ssn);
                console.log('admitted',admitted);

                $.ajax({
                    url: 'manager.php',
                    method: 'POST',
                    data: {
                        op: 'update_result',
                        ssn: ssn,
                        admitted: admitted
                    },
                    success: function(response) {
                        showMessage(checkbox);
                        // 這裡可以添加更新成功後的處理邏輯
                    },
                    error: function(xhr, status, error) {
                        console.error('更新失敗:', error);
                        // 這裡可以添加錯誤處理邏輯
                    }
                });
            });
        }).trigger('post-body.bs.table'); // 初次載入時也執行一次以綁定事件

            function showMessage(checkbox) {
                console.log('更新成功');
                var message = $('#update-message');
                if (message.length === 0) {
                    message = $('<div id="update-message" style="display: none; position: absolute; padding: 10px; background-color: rgba(51, 111, 214, 0.7); color: white; border-radius: 5px;z-index:1000;">更新成功</div>');
                    $('body').append(message);
                }
                var position = checkbox.offset();
                var checkboxWidth = checkbox.outerWidth();

                console.log('left',position.left);
                console.log('top',position.top);
                console.log('checkboxWidth',checkboxWidth);

                message.css({
                    left: position.left - 250,
                    top: position.top - 350
                }).fadeIn(500);

                setTimeout(function() {
                    message.fadeOut(500);
                }, 1000);
            }
        });

    </script>

<{/if}>