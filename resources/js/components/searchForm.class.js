;(function(factory) {
    module.exports = factory( 
        require('./util/class.js'),
        require('./form.class.js')
    );
}(function(ClassUtils, BaseFormClass) {
    'use strict';

    let FormSearch;

    return FormSearch = ClassUtils.Extend(BaseFormClass, function FormSearch($elem) {
        FormSearch.prototype.__super__.constructor.call(this, $elem);

        this.currentSearchData = {};
    }, {
        build: function(options) {
            FormSearch.prototype.__super__.build.call(this, options);

            var self                = this
                , searchCallback    = options.searchCallback
            ;

            this.currentSearchData = this.getData();

            this
                .on('cancel', function(cancelDeferred) {
                    cancelDeferred.resolve();

                    self.clear();
                })
                .on('submit', function(submitDeferred) {
                    self.currentSearchData = self.getData();

                    searchCallback(self.currentSearchData)
                        .always(function() {
                            submitDeferred.resolve();
                        });
                });

            this.$form
                .asyncContent(
                    'load',
                    function() {
                        var deferred = $.Deferred();

                        setTimeout(function() { deferred.resolve(); }, 500);

                        return deferred.promise();
                    }
                );
            
        }
    });
}));