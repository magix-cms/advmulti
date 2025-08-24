{strip}
    {if !isset($orientation)}
        {assign var="orientation" value="left"}
    {/if}
{/strip}
{if isset($advmulti) && $advmulti != null}
    <section id="advmulti" class="clearfix">
        <div class="container">
            <h2>Pour un résultat optimal, des services complémentaires sont proposés</h2>
            <div class="row">
                {strip}{capture name="class"}
                    {$nb = $advmulti|count}
                    adv
                    {*col-12{if $nb > 1 && $nb !== 3} col-sm-6{if $nb === 4} col-md-3{/if}{elseif $nb === 3} col-sm-4{/if}*}
                {/capture}{/strip}
                {foreach $advmulti as $adv}
                    <div class="{$smarty.capture.class}">
                        <div class="media media-{$orientation}">
                            <div class="media-title">
                                <div class="icon">
                                    <span class="material-icons {$adv.icon}"></span>
                                </div>
                                <div class="title">
                                    <p>{$adv.title}</p>
                                </div>
                            </div>
                            <div class="media-body icon-{$orientation}">
                                {if $nb !== 1}{$adv.desc = $adv.desc|truncate:500:'...'}{/if}
                                <p>{$adv.desc}</p>
                            </div>
                            {if $adv.url}<a href="{$adv.url}" title="{#read_more#} {$adv.title}" class="all-hover{if $adv.blank} targetblank{/if}"><span class="sr-only">{$adv.title}</span></a>{/if}
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>
{/if}