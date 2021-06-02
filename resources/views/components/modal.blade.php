<div class="modal pauseable fade" id="{{ $id }}">
    <div class="modal-dialog" style="margin-top: 10%; width: 50%;">
        <div class="modal-content">
        @if (isset($form)) 
            <form method="{{ $form['method'] }}" action="{{ $form['action'] }}">
        @endif
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ $title }}
                    </h4>
                </div>
                <div class="modal-body auto-height">
                @if (! isset($sync))
                    <section class="async-content"> 
                        <div class="target">
                @endif
                        @if (isset($description))
                            {{ $description }}
                        @endif 

                        @if (isset($form)) 
                            <p>
                                <span class="required"></span>&nbsp;は必須です。
                            </p>
                            <table class="table dl-table">
                                <tbody>
                                    {{ $content }}
                                </tbody>
                            </table>
                        @else 
                            {{ $content }}
                        @endif
                @if (! isset($sync))
                        </div>
                    </section>
                @endif
                </div>
            @if (isset($form)) 
                <div class="modal-footer">
                    <button 
                        class="btn btn-small-auto btn-primary btn-submit ladda-button" 
                        data-style="zoom-in" 
                        data-with-validation="true">登録</button>
                    <button 
                        class="btn btn-small-auto btn-default btn-cancel ladda-button" 
                        data-style="zoom-in">キャンセル</button>
                </div>
            @endif
        @if (isset($form)) 
            </form>
        @endif
        </div>
    </div>
</div>