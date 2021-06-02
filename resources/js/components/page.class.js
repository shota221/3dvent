;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('./util/class.js'),
        require('./util/observable.class'),
        require('ladda')
    );
}(function($, ClassUtils, Observable, Ladda) {
    'use strict';

    const 
        $page           = $('#page')

        , asyncable     = $page.hasClass('async-content')
        
        , transactional = $page.data('pageTransactional')
    ;

    /**
     * 通常ページ
     * 
     * @param {[type]} )             {                      } [description]
     * @param {[type]} options.load: function(callback) {                           var self [description]
     * @param {[type]} 500);                                                                                           }                            return deferred.promise();                        }                    );            } else {                callback( [description]
     */
    let Page = ClassUtils.Extend(Observable, function Page() {
        // 編集可能か
        this.pageEditable = 'true' == ($('#has-page-editable-role').val() || 'false') ? true : false;
    }, {
        load: function(callback) {
            var self = this;
            
            if (asyncable) {
                $page
                    .asyncContent(
                        'load',
                        function() {
                            var deferred = $.Deferred();

                            if (callback) {
                                callback.call(self, deferred);
                            } else {
                                setTimeout(function() { deferred.resolve(); }, 500);
                            }

                            return deferred.promise();
                        }
                    );
            } else {
                callback.call(self);
            }
        }
    });

    
    /**
     * 変更内容を一括で登録する必要があるページ
     * 
     * @param {[type]} )  {             PageTransactional.prototype.__super__.constructor.call(this);          this.$commitBtn [description]
     * @param {[type]} {          load: function(callback)                                            {                                        var self [description]
     */
    let PageTransactional = ClassUtils.Extend(Page, function PageTransactional() {
        PageTransactional.prototype.__super__.constructor.call(this);

        this.$commitBtn = $page.find('.btn-commit');
        
        this.$rollbackBtn = $page.find('.btn-rollback');

        this.LaddaCommit = Ladda.create(this.$commitBtn[0]);

        this.LaddaRollback = Ladda.create(this.$rollbackBtn[0]);

        this.$shouldCommitAlert = $page.find('.should-commit-alert');

        this.unCommitted = false;
    }, {
        load: function(callback) {
            var self = this;

            this.$commitBtn
                .prop('disabled', true)
                .on('click.page', function() {
                    if (! self.pageEditable) return false;

                    var data = $(this).data()
                        , deferred = $.Deferred()
                    ;

                    deferred
                        .then(
                            function() {
                                // resolve

                                self.unCommitted = false;

                                self.$shouldCommitAlert.hide();

                                self.LaddaCommit.stop();

                                self.$commitBtn.prop('disabled', true);
                            },
                            function() {
                                // reject

                                self.LaddaCommit.stop();
                            }
                        );

                    self.LaddaCommit.start();

                    self.trigger('commit', data.method, data.url, deferred);

                    return false;
                });

            this.$rollbackBtn
                .on('click.page', function() {
                    self.LaddaRollback.start();

                    setTimeout(function() {
                        location.reload();
                    }, 1);

                    return false;  
                });
            
            PageTransactional.prototype.__super__.load.call(this, callback); 
        }
        , alertShouldCommit: function() {
            if (! this.unCommitted) {
                if (! this.pageEditable) return;

                this.unCommitted = true;

                this.$commitBtn.prop('disabled', false)

                // $(window)
                //     .one('beforeunload.page', function(e) {
                //         e.returnValue = '更新したデータがある場合確定ボタンをクリックしないと登録されません。ページから離れますか？';
                //     });

                this.$shouldCommitAlert.show();
            }
        }
    });

    return transactional ? PageTransactional : Page;
}));