/**
 * 非同期生成テーブルページングクラス
 */
;(function(factory) {
    module.exports = factory( 
        jQuery,
        require('./util/class'),
        require('./table.class'),
        require('simple-pagination.js'), // jQuery.fn.pagination
    );
}(function($, ClassUtils, AsyncTableClass) {
    'use strict';

    let PaginationTable;

    return PaginationTable = ClassUtils.Extend(AsyncTableClass, function PaginationTable($tableArea) {
        PaginationTable.prototype.__super__.constructor.call(this, $tableArea);

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
            PaginationTable.prototype.__super__.build.call(this, options);

            var opts = $.extend({}, {
                limitRowCount   : this.$tableArea.data('tableLimitRowsCount') || 50,
                pagingCallback  : function(page) {}
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

            this.$pager && this.$pager
                .addClass('async-content')
                .asyncContent(
                    'load', 
                    function() {
                        return deferred.promise();
                    }, 
                    $('<div class="pagination" />').hide().appendTo(this.$pager)
                );

            return PaginationTable.prototype.__super__.loadRows.call(this, function(loadRowsDeferred) {
                paging.call(self, currentPage)
                    .done(function(data) {
                        initPager.call(self, data.totalCount, currentPage);

                        loadRowsDeferred.resolve(data.rowsData);

                        deferred.resolve();
                    });
            });
        }
    });

    function initPageCounter(totalCount, currentPage) {
        var self = this
            , itemsOnPage               = this.limitRowCount
            , firstItemCountOnThisPage  = (+currentPage - 1) * +itemsOnPage + 1
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
                            + ' 件';
                });
    }

    function destroyPageCounter() {
        if (! this.$pageCounter) return ;

        this.$pageCounter.empty();
    }


    function initPager(totalCount, currentPage) {
        if (0 === (totalCount || 0)) return ;

        var self = this
            , itemsOnPage = this.limitRowCount
            , opts = {
                currentPage     : (null == currentPage || 0 == +currentPage) ? 1 : currentPage,
                hrefTextPrefix  : '#page',
                items           : totalCount,
                itemsOnPage     : itemsOnPage,
                onPageClick     : function(page) { 
                    PaginationTable.prototype.__super__.loadRows.call(self, function(loadRowsDeferred) {
                        paging.call(self, page)
                            .done(function(data) {
                                loadRowsDeferred.resolve(data.rowsData);
                            });
                    });
                },
                displayedPages  : 1, //表示したいページング要素数
                prevText        : '<', //前へのリンクテキスト
                nextText        : '>', //次へのリンクテキスト
                cssStyle        : 'light-theme', //テーマ"dark-theme"、"compact-theme"があります
                limit           : itemsOnPage,
            }
        ;
            
        return this.$pager.each(function() {
            $(this).find('.pagination').pagination(opts);
        });
    }

    function destroyPager() {
        if (! this.$pager) return ;

        return this.$pager.each(function() {
            $(this).find('.pagination').pagination('destroy').end().empty();
        });
    }

    function paging(page) {
        var self = this
            , limit  = this.limitRowCount
            , offset = (page - 1) * limit
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