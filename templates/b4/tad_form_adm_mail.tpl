<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">

    <{if $op=="send"}>

        <{assign var="i" value=0}>
        <{foreach item=data from=$main}>
        <{if !$i}><div class="row"><{/if}>
            <div class="jumbotron col-sm-5">
            <span class="badge badge-info">To: <{$data.mail}></span>
            <span class="badge badge-success"><{$data.title}></span>
            <div><{$data.content}></div>
            </div>
        <{assign var="i" value=$i+1}>
        <{if $i == 2}></div><{assign var="i" value=0}><{/if}>
        <{/foreach}>

    <div class="clearfix"></div>

    <{else}>
        <form action="<{$xoops_url}>/modules/tad_form/mail.php" method="post" id="myForm" role="form">
        <div class="form-group row">
            <div class="col-sm-12">
            <input type="text" name="title" class="form-control" value="<{$title}>" placeholder="<{$smarty.const._MA_TADFORM_MAIL_TITLE}>">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
            <{$editor}>
            </div>
            <div class="col-sm-2">
            <span class="badge badge-danger"><{$smarty.const._MA_TADFORM_SEND_TAG}></span>
            <{$tag}>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="checkAll" checked>
                <label class="form-check-label" for="checkAll">全選</label>

                <input class="form-check-input" type="checkbox" name="test" id="test" value="1"  class="warning">
                <label class="form-check-label" for="test"><{$smarty.const._MA_TADFORM_MAIL_TEST}></label>
            </div>
            <{foreach item=data from=$data}>
                <div class="form-check form-check-inline">
                <input class="form-check-input email" type="checkbox" name="email[<{$data.man_name}>]" id="email-<{$data.man_name}>" value="<{$data.email}>" checked>
                <label class="form-check-label" for="email-<{$data.man_name}>"><{$data.man_name}></label>
                </div>
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
