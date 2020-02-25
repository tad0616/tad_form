<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">

    <{if $op=="send"}>

        <{assign var="i" value=0}>
        <{foreach item=data from=$main}>
        <{if !$i}><div class="row"><{/if}>
            <div class="jumbotron col-sm-5">
            <span class="label label-info">To: <{$data.mail}></span>
            <span class="label label-success"><{$data.title}></span>
            <div><{$data.content}></div>
            </div>
        <{assign var="i" value=$i+1}>
        <{if $i == 2}></div><{assign var="i" value=0}><{/if}>
        <{/foreach}>

    <div class="clearfix"></div>

    <{else}>
        <form action="<{$xoops_url}>/modules/tad_form/mail.php" method="post" id="myForm" class="form-horizontal" role="form">
        <div class="form-group">
            <div class="col-sm-12">
            <input type="text" name="title" class="form-control" value="<{$title}>" placeholder="<{$smarty.const._MA_TADFORM_MAIL_TITLE}>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
            <{$editor}>
            </div>
            <div class="col-sm-2">
            <span class="label label-danger"><{$smarty.const._MA_TADFORM_SEND_TAG}></span>
            <{$tag}>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <label class="checkbox-inline">
                    <input type="checkbox" id="checkAll" checked>全選
                </label>

                <label class="checkbox-inline">
                    <input type="checkbox" name="test" id="test" value="1" class="warning"><{$smarty.const._MA_TADFORM_MAIL_TEST}>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <{foreach item=data from=$data}>
                    <label class="checkbox-inline">
                        <input name="email[<{$data.man_name}>]" type="checkbox" class="email" value="<{$data.email}>" checked><{$data.man_name}>
                    </label>
                <{/foreach}>
            </div>
        </div>

        <div class="text-center">
            <input type="hidden" name="op" value="send">
            <input type="hidden" name="ofsn" value="<{$ofsn}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._MA_TADFORM_SEND}></button>
        </div>
        </form>
    <{/if}>
</div>

<script type='text/javascript'>
    $(document).ready(function(){
        $("#checkAll").change(function() {
            $(".email").each(function() {
                $(this).prop("checked", $("#checkAll").prop('checked'));
            });
        });

    });
</script>
