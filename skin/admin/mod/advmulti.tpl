<div class="row">
    <form id="edit_advmulti" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}{if isset($smarty.get.edit)}&amp;action=edit&amp;edit={$smarty.get.edit}{/if}&amp;plugin={$smarty.get.plugin}&amp;mod_action={if !$edit}add{else}edit{/if}" method="post" class="validate_form{if !$edit} add_form collapse in{else} edit_form{/if} col-ph-12 col-sm-8 col-md-6">
        <div class="row">
            <div class="form-group col-ph-12 col-sm-6 col-md-6">
                <label for="icon_adv">
                    {#icon_advmulti#|ucfirst}&nbsp;*&nbsp;
                    <a href="#" class="icon-help text-info"
                       data-toggle="popover"
                       data-title="{#how_to_use#}">
                        <span class="fa fa-question-circle"></span>
                    </a>
                </label>
                {*<input type="text" class="form-control" id="icon_advmulti" name="advmulti[icon_advmulti]" value="{$advmulti.icon_advmulti}" />*}
                <select name="advmulti[icon_advmulti]" id="icon_advmulti" class="form-control">
                    <option value="">{#ph_icon#|ucfirst}</option>
                    {foreach $iconnames as $key}
                        <option value="{$key}" {if $key == $advmulti.icon_advmulti} selected{/if}>{$key}</option>
                    {/foreach}
                </select>
                <div id="popover-content" class="hide">
                    <p>{#cu_content#}</p>
                    <p><a href="http://icomoon.io/">http://icomoon.io/</a></p>
                    <img src="{$url}/plugins/advmulti/img/cu-help.jpg" alt="Help using"/>
                </div>
            </div>
        </div>
        {include file="language/brick/dropdown-lang.tpl"}
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <div role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <fieldset>
                                <legend>Texte</legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                        <div class="form-group">
                                            <label for="title_advmulti_{$id}">{#title_advmulti#|ucfirst} :</label>
                                            <input type="text" class="form-control" id="title_advmulti_{$id}" name="advmulti[content][{$id}][title_advmulti]" value="{$advmulti.content[{$id}].title_advmulti}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="desc_advmulti_{$id}">{#desc_advmulti#|ucfirst} :</label>
                                            <textarea class="form-control" id="desc_advmulti_{$id}" name="advmulti[content][{$id}][desc_advmulti]" cols="65" rows="3">{$advmulti.content[{$id}].desc_advmulti}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                                        <div class="form-group">
                                            <label for="published_advmulti_{$id}">Statut</label>
                                            <input id="published_advmulti_{$id}" data-toggle="toggle" type="checkbox" name="advmulti[content][{$id}][published_advmulti]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($advmulti) && $iso@first) || $advmulti.content[{$id}].published_advmulti} checked{/if}>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Options</legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="url_advmulti_{$id}">{#url_advmulti#|ucfirst} :</label>
                                            <input type="text" class="form-control" id="url_advmulti_{$id}" name="advmulti[content][{$id}][url_advmulti]" value="{$advmulti.content[{$id}].url_advmulti}" size="50" />
                                        </div>
                                        <div class="form-group">
                                            <label for="blank_advmulti_{$id}">{#blank_advmulti#|ucfirst}</label>
                                            <div class="switch">
                                                <input type="checkbox" id="blank_advmulti_{$id}" name="advmulti[content][{$id}][blank_advmulti]" class="switch-native-control"{if $advmulti.content[{$id}].blank_advmulti} checked{/if} />
                                                <div class="switch-bg">
                                                    <div class="switch-knob"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <fieldset>
            <legend>Enregistrer</legend>
            {if $edit}
                <input type="hidden" name="advmulti[id]" value="{$advmulti.id_advmulti}" />
            {/if}
            <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>