<div id="department_features_{$block.block_id}">

    {if !$no_pagination}
        {include file="common/pagination.tpl"}
    {/if}

    <div class="ty-feature">
        {if $department_data.main_pair}
            <div class="ty-feature__image">
                {include file="common/image.tpl" images=$department_data.main_pair}
            </div>
        {/if}
        <div class="ty-feature__description ty-wysiwyg-content">
            {$department_data.description nofilter}
        </div>
    </div>

    {if ($director)}
        <div class="ty-compact-list ty-mb-m">
            <h4>{__('director')}</h4>
            <div class="ty-compact-list__item">
                <div class="ty-compact-list__content">
                    <div class="ty-compact-list__title">
                        <p>{$director.user_id}.</p>
                    </div>
                    <div class="ty-compact-list__title">
                        <p>{$director.firstname} {$director.lastname}</p>
                    </div>
                    <div class="ty-compact-list__title">
                        <p>{$director.email}</p>
                    </div>
                </div>
            </div>
        </div>
    {/if}

    {if ($users)}
        <div class="ty-compact-list ty-mb-m ty-mt-l">
            <h4 class="ty-mb-m">{__('employees')}</h4>
            <div class="ty-compact-list__content">
                <div class="ty-compact-list__title">
                    <i>{__('name')} :</i>
                </div>
                <div class="ty-compact-list__title">
                    <i>{__('email')} :</i>
                </div>
                <div class="ty-compact-list__title">
                    <i>{__('company')} :</i>
                </div>
            </div>
            <hr>
            {foreach from=$users item=user}
                <div class="ty-compact-list__item">
                    <div class="ty-compact-list__content">
                        <div class="ty-compact-list__title">
                            <p>{$user.firstname} {$user.lastname}</p>
                        </div>
                        <div class="ty-compact-list__title">
                            <p>{$user.email}</p>
                        </div>
                        <div class="ty-compact-list__title">
                            <p>{$user.company_name}</p>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {/if}

    {if !$no_pagination}
        {include file="common/pagination.tpl"}
    {/if}
</div>

{capture name="mainbox_title"}{$department_data.department nofilter}{/capture}