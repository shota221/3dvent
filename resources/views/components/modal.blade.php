<div class="modal pauseable fade" id="{{ $id }}">
    <div class="modal-dialog" style="margin-top: 10%; width: 50%;">
        <div class="modal-content">
            @if (isset($form))
                <form name="{{ $form['name'] }}">
            @endif
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ $title }}
                </h4>
            </div>
            <div class="modal-body auto-height">
                @if (isset($description))
                    {{ $description }}
                @endif

                @if (isset($form))
                    <p>
                        <span class="required"></span>@lang('messages.is_required_item')
                    </p>
                @endif
                {{ $content }}
            </div>
            @if (isset($form))
                </form>
                <div class="modal-footer">
                    <button class="btn btn-small-auto btn-primary" data-url="{{ $form['action'] }}"
                        data-method="{{ $form['method'] }}" id="async-{{ $form['name'] }}">@lang('messages.'.strtolower($form['method']))</button>
                    <button class="btn btn-small-auto btn-default modal-cancel">@lang('messages.back')</button>
                </div>
            @endif
        </div>
    </div>
</div>
