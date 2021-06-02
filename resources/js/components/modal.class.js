/**
 * 非同期生成テーブルクラス
 */
;(function(factory) {
    module.exports = factory( 
        require('./util/class.js'),
        require('./util/observable.class.js')
    );
}(function(ClassUtils, Observable) {

    return ClassUtils.Extend(Observable, function Modal($elem) {
        this.$modal = $elem;

        this.$modalAreaLoadingSection;
    }, {
        build: function(options) {
            var opts;

            opts = $.extend({}, {
                $loadingSection: this.$modal.find('section.async-content')
            }, options || {});

            this.$modalAreaLoadingSection = opts.$loadingSection.asyncContent();

            return this;
        }
        , load: function(loadCallback, timeout) {
            if (this.$modalAreaLoadingSection.length) {
                this.$modalAreaLoadingSection
                    .asyncContent('load', function() {
                        var deferred = $.Deferred();
                        
                        if (timeout) {
                            setTimeout(function() { 
                                loadCallback(); 

                                deferred.resolve();
                            }, timeout)
                        } else {
                            loadCallback(deferred);
                        }

                        return deferred.promise();
                    })
            } else {
                loadCallback();
            }
        }
        , open: function(loadCallback) {
            var self = this;

            this.$modal
                .one('show.bs.modal', function() {
                    setTimeout(function() {
                         self.trigger('show');
                    }, 0);
                })
                .one('shown.bs.modal', function() {
                    setTimeout(function() {
                        self.trigger('shown');
                    }, 0);
                })
                .modal('show')
                .focus();
        }
        , close: function() {
            var self = this
                , $parts = $('.modal-header, .modal-body, modal-footer')
            ;

            this.$modal
                .one('hide.bs.modal', function() {
                    setTimeout(function() {
                         self.trigger('hide');
                    }, 0);
                })
                .one('hidden.bs.modal', function() {
                    setTimeout(function() {
                        self.trigger('hidden');

                        $parts.css({ opacity: 1.0 });
                    }, 0);
                });

            $parts.animate({opacity: 0.0}, {duration: 400, complete: function() {
                self.$modal.modal('hide');
            }});
        }
    });

}));