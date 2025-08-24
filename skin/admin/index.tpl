{extends file="layout.tpl"}
{block name='head:title'}banner{/block}
{block name='body:id'}banner{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                {#nbr_banner#|ucfirst}: {$banners|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=banner&amp;action=add" title="{#add_banner#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_banner#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">banner</h1>
{/block}
{block name='article:content'}
{if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
    <section class="panel col-xs-12 col-md-12">
    {if $debug}
        {$debug}
    {/if}
    <header class="panel-header">
        <h2 class="panel-heading h5">Gestion de banner</h2>
    </header>
    <div class="panel-body panel-body-form">
        <div class="mc-message-container clearfix">
            <div class="mc-message"></div>
        </div>
        {include file="section/form/table-form-2.tpl" data=$banners idcolumn='id_banner' activation=false search=false sortable=true controller="banner"}
    </div>
    </section>
    </div>
    {include file="modal/delete.tpl" data_type='banner' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_banner_message#}}
    {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js,
        plugins/banner/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof banner == "undefined")
            {
                console.log("banner is not defined");
            }else{
                banner.run();
            }
        });
    </script>
{/block}
