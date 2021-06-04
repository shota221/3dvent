/********************************
 * Bootstrap Modal 拡張
 ********************************/

 if (typeof jQuery === 'undefined') throw new Error('required jQuery');

;(function($) {
    /**
     * .modal.transparent
     * 
     * 背景クリックでダイアログを非表示にし、背景を見えるようにカスタマイズ
     */
    $('.modal.pauseable')
        .on('shown.bs.modal', function () {
            var $body = $('body');

            var $dialog = $(this).find('.modal-dialog');

            var bodyW = $body.width();

            var dialogW = $dialog.outerWidth();

            var dialogContentW = $dialog.find('.modal-content').width();

            $(this)
                .off('click')
                .on('click', function(ev) {
                    if (!$(ev.target).is($(this))) return false;

                    if (!$dialog.hasClass('pause')) {
                        $body.removeClass('modal-open'); // overflow: hidden; を解除しスクロール可能にする

                        //$dialog.animate({ 'margin-right': -(bodyW / 2 + dialogW / 2) + 100 }, { complete: function() { $(this).addClass('pause'); } });
                        $dialog.animate({ 'margin-right':  -(dialogW / 2 + dialogContentW / 2) + 100 }, { complete: function() { $(this).addClass('pause'); } });
                        $('.modal-backdrop.in').animate({ 'opacity': 0.0 })
                    } else {
                        $body.addClass('modal-open'); // overflow: hidden; をもどしスクロール不可能にする

                        //$dialog.animate({ 'margin-right': (bodyW / 2) - (dialogW / 2) }, { complete: function() { $(this).removeClass('pause'); }});
                        $dialog.animate({ 'margin-right': (bodyW / 2) - (dialogW / 2) }, { complete: function() { $(this).removeClass('pause'); }});
                        $('.modal-backdrop.in').animate({ 'opacity': 0.5 })
                    }
                    
                    return false;
                });
        });

})(jQuery);