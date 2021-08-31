{{-- 絞込検索 --}}
<div class="post" id="{{ $id }}">
    <h6>@lang('messages.refined_search')</h6>
    <form id="async-search-form">
        {{ $content }}
    </form>
    <div class="btn-toolbar">

        {{-- 検索条件をクリア --}}
        <div class="btn-group">
            <button class="btn btn-default btn-sm" id="clear-search-form">@lang('messages.clear_search_form')</button>
        </div>

        {{-- 検索 --}}
        <div class="btn-group ml-auto">
            <button class="btn btn-secondary btn-sm" data-url="{{ $action }}"
            data-method="get" id="async-search">@lang('messages.search')</button>
        </div>
    </div>
<<<<<<< HEAD
</div>
=======
</div>
>>>>>>> c84f601... 検索機能実装
