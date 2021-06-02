/**
 * 非同期生成テーブルページングクラス
 */
;(function(factory) {
    module.exports = factory( 
        jQuery,
        require('./util/class'),
        require('./table.class'),
        require('ladda'),
    );
}(function($, ClassUtils, AsyncTableClass, Ladda) {
    'use strict';

    const pagerButtonHtml = '' 
        + '<button type="submit" class="btn btn-success btn-block" data-style="zoom-in">'
        + '次の&nbsp;<b></b>&nbsp;件を表示'
        + '</button>';

    let ContinuousTable;

    return ContinuousTable = ClassUtils.Extend(AsyncTableClass, function ContinuousTable($tableArea) {
        ContinuousTable.prototype.__super__.constructor.call(this, $tableArea);

        this.$pager = $('.table-pager' + '.' + this.tableAreaId);

        this.$pageCounter = $('.page-counter' + '.' + this.tableAreaId);

        this.limitRowCount;
    }, {
        /**
         * Overriding
         * 
         * @param  {[type]} options [description]
         * @return {[type]}         [description]
         */
        build: function(options) {
            ContinuousTable.prototype.__super__.build.call(this, options);

            var opts = $.extend({}, {
                limitRowCount   : this.$tableArea.data('tableLimitRowsCount') || 50,
                pagingCallback  : function(page) {},
            }, options || {});

            this.limitRowCount = opts.limitRowCount;

            this.pagingCallback = opts.pagingCallback;

            return this;
        }
        /**
         * 
         * @return {[type]} [description]
         */
        , load: function() {
            var self = this;

            var currentPage = 1; // TODO hash値#pageから取得

            var deferred = $.Deferred();

            destroyPager.call(this);

            return ContinuousTable.prototype.__super__.loadRows.call(this, function(loadRowsDeferred) {
                paging.call(self, currentPage)
                    .done(function(data) {
                        initPager.call(self, data.totalCount, currentPage);

                        loadRowsDeferred.resolve(data.rowsData);
                    });
            });
        }
    });

    function initPageCounter(totalCount, currentPage) {
        var self = this
            , itemsOnPage               = this.limitRowCount
            , firstItemCountOnThisPage  = 1
            , thisPageWillItems         = +currentPage * +itemsOnPage
        ;

        this.$pageCounter
            .find('.counter')
                .html(function() {
                    if (firstItemCountOnThisPage > totalCount) return '';
                    
                    return '<span class="total">' + totalCount + '</span>'
                            + ' 件中 : '
                            + '<span class="current">' 
                            + firstItemCountOnThisPage
                            + ' ～ ' 
                            + (thisPageWillItems < +totalCount ? thisPageWillItems : totalCount)
                            + '</span>'
                            + ' 件を表示';
                });
    }

    function destroyPageCounter() {
        if (! this.$pageCounter) return ;

        this.$pageCounter.empty();
    }


    function initPager(totalCount, currentPage) {
        if (0 === (totalCount || 0)) return ;

        var self = this
            , limit = this.limitRowCount
            , currentPage = +currentPage
            , LaddaPager
        ;

        function handlePager(totalCount, currentPage) {
            var restCount = totalCount - currentPage * limit;

            if (restCount <= 0) {
                destroyPager.call(self);

                return false;
            } else {
                self.$pager
                    .html(pagerButtonHtml)
                    .find('b')
                        .html((restCount > limit) ? limit : restCount);
            }

            return true;
        }

        if (! handlePager(totalCount, currentPage)) return ;
            
        this.$pager
            .on('click.pager', function() {
                var nextPage = currentPage + 1;

                LaddaPager = Ladda
                    .create($(this).find('button')[0])
                    .start();

                self
                    .addRows.call(self, function() {
                        return paging.call(self, nextPage)
                            .then(function(data) {
                                currentPage = nextPage;

                                handlePager(data.totalCount, currentPage);

                                return data.rowsData;
                            });
                    })
                    .done(function() {
                        LaddaPager.stop().remove();
                    })

                return false;
            });
    }

    function destroyPager() {
        return this.$pager.each(function() {
            $(this).off('.pager').empty();
        });
    }

    function paging(page) {
        var self = this
            , limit  = this.limitRowCount
            , offset = (+page - 1) * limit
            , deferred = $.Deferred()
        ;

        destroyPageCounter.call(self);

        this.$pageCounter && this.$pageCounter
            .addClass('async-content')
            .asyncContent(
                'load', 
                function() {
                    return deferred.promise();
                }, 
                $('<div class="counter" />').hide().appendTo(this.$pageCounter)
            );

        return self.pagingCallback.call(self, offset, limit)
            .then(
                function(totalCount, rowsData) {
                    initPageCounter.call(self, (totalCount || 0), page);

                    deferred.resolve();

                    return {
                        totalCount: totalCount, 
                        rowsData:   rowsData
                    };
                },   
                function() {
                    return {
                        totalCount: 0,
                        rowsData:   []
                    };
                }
            )
            .always(function() {
                deferred.resolve();
            });
    }

}));