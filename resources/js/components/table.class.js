/**
 * 非同期生成テーブルクラス
 */
;(function(factory) {
    module.exports = factory( 
        jQuery,
        i18n,
        require('./util/class.js'),
        require('./util/observable.class.js'),
        require('ladda'),
        require('cookie'),
        require('./util/functions'),
        require('./jquery/jquery.table.resizableColumns'),
    );
}(function($, i18n, ClassUtils, Observable, Ladda, Cookie) {
    'use_strict';

    const tableHtml = ''
        + '<table class="table async table-striped table-hover table-bordered no-margin">'
        + '<thead></thead>'
        + '<tbody></tbody>'
        + '</table>';

    const contentHtml = '' 
        + '<div class="panel panel-default">'
        + '<div class="panel-heading">' 
        + '<h4></h4>'
        + '</div>'
        + tableHtml
        + '</div>';

    const noContentHtml = '' 
        + '<div class="no-content"> '
        + '<div class="text-center clearfix">' 
        + '<p>' + i18n('message.no_data') + '</p>'
        + '</div>'
        + '</div>';

    const rowOverlayHtml = '' 
        + '<div class="content-overlay">' 
        + '<div class="loading"></div>' 
        + '</div>';

    const loaderHtml = '<div class="loading" />';


    /**
    * カラム定義クラス
    */

    /**********************************
     * Class ColumnDefinition
     **********************************/

    const ColumnDefinitionClass = ClassUtils.Class(function ColumnDefinition(headerColumnData) {

        this.name = headerColumnData.name || '';

        this.type = headerColumnData.type;

        this.text = headerColumnData.text || '';

        this.size = headerColumnData.size || 'auto';

        // this.sizeSuffix = /\d+%/.test(this.size) ? '%' : 'px';

        // this.sizeInt = this.size.replace(this.sizeSuffix, '');

        this.align = ('currency' === this.type || 'amount' === this.type || 'integer' === this.type ? 'right' : 'left');
    }, {
        /**
         * カラムの値にコンバート
         * 
         * @param   columnData 
         * @return string
         */
        convert: function(rowData) {
            if (null === rowData) return null;
            
            var value, data = rowData[this.name];

            if (typeof data === 'undefined') {
                if (this.type !== 'html' && this.type !== 'blank') return '';
            }

            switch (this.type) {
                case 'currency': 
                    var num = Number(data);
                    
                    value = num.currency(); 

                    if (0 > num) value = '<span class="deficit">' + value + '</span>'

                    break;
                case 'date':
                    value = data.replace(' ', '<br />');

                    break;
                case 'amount':
                    value = (Number(data)).currency(); 

                    break;
                case 'html':
                    switch (this.name) {
                        case 'edit'  : 
                            value = '<span class="btn btn-sm btn-success edit ladda-button" data-style="zoom-in"><span class="glyphicon glyphicon-pencil"></span></span>'; 

                            break;
                        case 'delete': 
                            value = '<span class="btn btn-sm btn-danger delete ladda-button" data-style="zoom-in"><span class="glyphicon glyphicon-trash"></span></span>'; 

                            break;
                        default:
                            value = data;
                    }

                    break;
                case 'text':
                    value = String(data).escapeHTML().n2br();

                    break;
                case 'blank':
                    value = '&nbsp;';

                    break;
                default:        
                    value = (String(data)).escapeHTML();
            }
            
            return value;
        }
    });


    /**********************
     * Class AsyncTable
     **********************/

    return ClassUtils.Extend(Observable, function AsyncTable($elem) {
        this.$tableArea = $elem;

        this.tableAreaId = $elem.attr('id');

        this.ColumnDefinitionClass = ColumnDefinitionClass;

        this.ColumnDefinitions;

        this.$content;

        this.$table;

        this.$thead;

        this.$tbody;

        this.$addRowBtn = $('.btn-add-row' + '.' + this.tableAreaId).prop('disabled', true);

        this.$downloadBtn = $('.btn-download' + '.' + this.tableAreaId).prop('disabled', true);

        this.disabledColumnNames = [];
    }, {
        /**
         * 生成
         * 
         * @param  Object options
         */
        build: function(options) {
            var self = this;

            var storedColumnSizes = (function() {
                var stored = (Cookie.parse(document.cookie) || {})[self.tableAreaId + '-columns-size'];

                return stored ? $.parseJSON(stored) : null;
            })();

            var setStoredColumnSizes = function() {
                var name    =  self.tableAreaId + '-columns-size'
                    , data  = self.$table
                        .find('th')
                            .map(function(index, e) { return e.clientWidth; })
                            .get()
                ;

                document.cookie = Cookie.serialize(
                    name, 
                    JSON.stringify(data),
                    {
                        maxAge  : null,
                        path    : '/',
                        sameSite: true,
                    }
                );
            }

            var opts = $.extend({}, {
                title                   : null,
                tableClassName          : '',
                columnResizable         : this.$tableArea.data('tableResizable'),
                headerColumnsData       : this.$tableArea.data('tableColumnDefinition'),
                addRowManuallyCallback  : null,
                outputCallback          : null,
                disabledColumnNames     : [],
            }, options || {});

            var headerHtml = '';

            // BUILD TABLE

            if (opts.title) {
                this.$content = $(contentHtml).find('h4').html(opts.title).end();

                this.$table = this.$content.find('table');
            } else {
                this.$content = this.$table = $(tableHtml);
            }

            this.$table.addClass(opts.tableClassName);

            this.$tbody = this.$table.find('tbody');

            // THEAD 

            // column 定義
            this.ColumnDefinitions = opts.headerColumnsData.map(function(headerColumnData) {
                return new (self.ColumnDefinitionClass)(headerColumnData); 
            });

            this.ColumnDefinitions.forEach(function(ColumnDefinition, index) {
                var columnSize = storedColumnSizes ? storedColumnSizes[index] + 'px' :  ColumnDefinition.size;

                headerHtml += '<th style="width:' + columnSize + ';">' + ColumnDefinition.text + '</th>';
            });

            this.$thead = this.$table.find('thead').html('<tr>' + headerHtml + '</tr>');
                
            this.$tableArea.append(this.$content);
            

            // BIND EVENT

            if (opts.columnResizable) {
                this.$table
                    .addClass('table-resizable')
                    .on('resizedColumns', function() {
                        setStoredColumnSizes();
                    })
                    .resizableColumns();
            }

            if (opts.addRowManuallyCallback) {
                this.$addRowBtn
                    .prop('disabled', false)
                    .on('click.table', function() {
                        var LaddaAddRow = Ladda.create($(this)[0]).start();

                        setTimeout(function() {
                            $.when(opts.addRowManuallyCallback.call(self))
                                .always(function() {
                                   LaddaAddRow.stop().remove();
                                });
                        }, 5);

                        return false;
                    });
            }

            if (opts.outputCallback) {
                this.$downloadBtn
                    .prop('disabled', false)
                    .on('click.table', function() {
                        var LaddaOutput = Ladda.create($(this)[0]).start();

                        setTimeout(function() {
                            $.when(opts.outputCallback.call(self))
                                .always(function() {
                                   LaddaOutput.stop().remove();
                                });
                        }, 5);

                        return false;
                    });
            }

            if (opts.disabledColumnNames) {
                this.disabledColumnNames = opts.disabledColumnNames;

                this.$table
                    .on('click.table', 'td.disabled', function(ev) {
                        ev.stopImmediatePropagation();

                        return false;
                    });
            }

            this.$table
                .on('click.table', 'tr.tRow td.edit', function() {
                    var rowIndex = $(this).parents('tr.tRow').index();

                    self.trigger('edit', rowIndex);

                    return false;
                })
                .on('click.table', 'tr.tRow td.delete', function() {
                    var rowIndex = $(this).parents('tr.tRow').index();

                    self.trigger('delete', rowIndex);

                    return false;
                });

            return this;
        }
        /**
         * 全列ロード
         * 
         * @param  {[type]} loadCallback [description]
         * @param  {[type]} timeout      [description]
         * @return {[type]}              [description]
         */
        , loadRows: function(rowsData, timeout) {
            var self = this
                , deferred = $.Deferred()
                , $loader = $(loaderHtml).appendTo(this.$tableArea)
            ;

            if (typeof rowsData === 'function') {
                rowsData.call(this, deferred);
            } else {
                setTimeout(function() { deferred.resolve(rowsData); }, timeout || 1000);
            }

            this.$tbody.css({ 'opacity': 0.0 });

            self.clearRows();

            return deferred.promise()
                .then(
                    function(data) {
                        (! data.length) && noContent.call(self);

                        self.addRows(data);

                        return data;
                    }
                )
                .always(function(data) {
                    $loader.animate({ opacity: 0.0 }, { duration: 500, complete: function() {
                        $loader.remove();
                    }});

                    self.$tbody.animate({ opacity: 1.0 }, { duration: 500, queue: true });

                    return data;
                })
        }
        /**
        * 列追加
        * 
        * @param Object rowsData 列データ
        */
        , addRows: function(rowsData) {
            var self = this
                , deferred = $.Deferred()
                , append = function(data, async) {
                    var rowsHtml = ''
                        , rowHtmlStartTag = (async ? '<tr class="tRow added" style="opacity: 0.0;">' : '<tr class="tRow added">')
                        , $addedRows
                    ;

                    data.forEach(function(rowData) {
                        var columnsHtml = '';

                        self.ColumnDefinitions.forEach(function(ColumnDefinition) {
                            var columnName, columnAlign, columnData, columnText, columnClass;

                            columnName = ColumnDefinition.name;

                            columnAlign = ColumnDefinition.align;

                            columnValue = ColumnDefinition.convert(rowData);

                            columnClass = columnName + (-1 !== self.disabledColumnNames.indexOf(columnName) ? ' disabled' : '');

                            columnsHtml += '<td align="' + columnAlign + '" class="' + columnClass + '">' + (columnValue || '-') + '</td>';
                        });

                        rowsHtml += rowHtmlStartTag + columnsHtml + '</tr>';
                    });

                    self.$tbody.append(rowsHtml);

                    $addedRows = self.$tbody.find('.tRow.added');

                    self.trigger('addedRows', data, $addedRows);

                    if (async) {
                        var $addedFirstRow = $addedRows.eq(0);

                        $addedFirstRow.find('td').css({ 'border-top': '2px solid #ddd' });

                        $('html,body').stop().animate({ scrollTop : $addedFirstRow.offset().top }, 500);

                        $addedRows.animate({ opacity: 1.0 }, { duration: 1000, queue: true, complete: function(){
                            $addedRows.removeClass('added');

                            deferred.resolve(data);
                        }})
                    } else {
                        $addedRows.removeClass('added');

                        deferred.resolve(data);
                    }
                }
            ;             

            if (typeof rowsData === 'function') {
                rowsData.call(this).done(function(data) {
                    append(data, true);
                });
            } else {
                append(rowsData, false);
            }

            return deferred.promise(); 
        }
        /**
         * 列更新
         * 
         * @param  {[type]} rowIndex [description]
         * @param  {[type]} rowData  [description]
         * @return {[type]}          [description]
         */
        , updateRow: function(rowIndex, rowData) {
            this.clearRowError(rowIndex);

            var self = this
                , $row = this.$tbody.find('tr.tRow').eq(rowIndex)
                , $overlay = $(rowOverlayHtml)
                    .css({
                        top     : $row.offset().top - this.$table.offset().top, 
                        left    : $row.offset().left - this.$table.offset().left,
                        height  : $row.outerHeight(),
                        width   : $row.outerWidth()
                    })
                    .addClass('row-overlay-index-' + rowIndex)
                    .appendTo(this.$table)
                    .show()
                , deferred = $.Deferred()
            ;

            if (typeof rowData === 'function') {
                rowData.call(this, deferred);
            } else {
                setTimeout(function() { deferred.resolve(rowData); }, 1000);
            }

            return deferred.promise()
                .then(
                    function(data) {
                        self.ColumnDefinitions.forEach(function(ColumnDefinition) {
                            var columnValue = ColumnDefinition.convert(data);
                            
                            $row.find('td.' + ColumnDefinition.name).html(columnValue || '-');
                        });

                        self.trigger('updatedRow', rowIndex, data, $row);
                    },
                    function(errors) {
                        self.handleRowError(errors, rowIndex);
                    }
                )
                .always(function() {
                    $overlay.animate({ opacity: 0.0 }, { duration: 500, complete: function() {
                        $overlay.remove();
                    }})
                });

        }
        /**
         * 列削除
         * 
         * @param  {[type]} rowIndex [description]
         * @return {[type]}          [description]
         */
        , removeRow(rowIndex) {
            var self = this
                , $rows = this.$tbody.find('tr.tRow')
                , $row = $rows.eq(rowIndex)
                , deferred = $.Deferred()
            ;

            $row
                .animate(
                    { 
                        'opacity': 0.0 
                    }, {   
                        duration: 500, 
                        queue   : true, 
                        complete: function() {
                            if (1 === $rows.length) {
                                self.clearRows();

                                noContent.call(self);
                            } else {
                                $(this).remove();
                            }

                            deferred.resolve();
                        }
                    });

            return deferred.promise()
                .then(
                    function() {
                        self.trigger('removedRow', rowIndex);
                    },
                    function() {
                    }
                );
        }
        /**
         * カラム更新
         * 
         * @param  {[type]} rowIndex   [description]
         * @param  {[type]} columnData [description]
         * @return {[type]}            [description]
         */
        , updateColumn: function(rowIndex, columnName, columnValue) {
            this.clearColumnError(rowIndex, columnName);

            var self = this
                , $column = self.$tbody.find('tr.tRow').eq(rowIndex).find('td.' + columnName)
                , $overlay = $(rowOverlayHtml)
                    .css({
                        top     : $column.offset().top - self.$table.offset().top, 
                        left    : $column.offset().left - self.$table.offset().left,
                        height  : $column.outerHeight(),
                        width   : $column.outerWidth()
                    })
                    .appendTo(self.$table)
                    .show()
                , deferred = $.Deferred()
            ;

            if (typeof columnValue === 'function') {
                columnValue.call(this, deferred);
            } else {
                setTimeout(function() { deferred.resolve(columnValue); }, 1000);
            }

            return deferred.promise()
                .then(
                    function(data) {
                        self.ColumnDefinitions.forEach(function(ColumnDefinition) {
                            if (ColumnDefinition.name === columnName) {
                                var data = {};

                                data[columnName] = columnValue;
                                
                                $column.html(ColumnDefinition.convert(data) || '-');

                                self.trigger('updatedColumn', data, $column);

                                return ;
                            }
                        });
                    },
                    function(error) {
                        self.handleRowError(error, rowIndex);
                    }
                )
                .always(function() {
                    $overlay.animate({ opacity: 0.0 }, { duration: 500, complete: function() {
                        $overlay.remove();
                    }});
                });
        }
        /**
         * 全列削除
         * 
         * @return {[type]} [description]
         */
        , clearRows: function() {
            this.$tableArea.find('.no-content').remove();

            this.$tbody.empty();
        }
        /**
         * 全列エラー表示
         * 
         * @param  {[type]} errors [description]
         * @return {[type]}        [description]
         */
        , handleErrors: function(errors) {
            var self = this;

            $.each(errors, function(rowIndex, error) {
                self.handleRowError(error, rowIndex);
            });
        }
        /**
         * 列単位エラー表示
         * 
         * @param  {[type]} error    [description]
         * @param  {[type]} rowIndex [description]
         * @return {[type]}          [description]
         */
        , handleRowError: function(error, rowIndex) {
            var self = this;

            $.each(error, function(columnName, messages) {
                createFieldError(
                    self.$tbody.find('tr.tRow').eq(rowIndex).find('td.' + columnName),
                    messages
                );
            });
        }
        /**
         * カラム単位エラー表示
         * 
         * @param  {[type]} error    [description]
         * @param  {[type]} rowIndex [description]
         * @param  {[type]} columnName      [description]
         * @return {[type]}          [description]
         */
        , handleColumnError: function(error, rowIndex, columnName) {
            // TODO 参照されていないからあとで実装
        }
        /**
         * 全列エラークリア
         * @return {[type]} [description]
         */
        , clearErrors: function() {
            clearFieldError(this.$tbody);
        }
        /**
         * 列単位エラークリア
         * @return {[type]} [description]
         */
        , clearRowError: function(rowIndex) {
            clearFieldError(this.$tbody.find('tr.tRow').eq(rowIndex));
        }
        /**
         * カラム単位エラークリア
         * 
         * @param  {[type]} rowIndex   [description]
         * @param  {[type]} columnName [description]
         * @return {[type]}            [description]
         */
        , clearColumnError: function(rowIndex, columnName) {
            clearFieldError(this.$tbody.find('tr.tRow').eq(rowIndex).find('td.' + columnName));
        }
        /**
         * destroy
         * 
         * @return {[type]} [description]
         */
        , destroy: function() {
            this.ColumnDefinitions = null;

            this.off();

            this.$table.off('.table');

            this.$addRowBtn.off();

            this.$downloadBtn.off();

            this.$content.empty().remove();
        }
    });

    /**
     * 列なし
     * 
     * @return {[type]} [description]
     */
    function noContent() {
        this.$tableArea.append(noContentHtml);
    }

    /**
     * エラー表示
     * 
     * @param  {[type]} $td    [description]
     * @param  {[type]} errors [description]
     * @return {[type]}        [description]
     */
    function createFieldError($td, errors) {
        if (errors) {
            $td.prepend('<p class="ng-alert">' + errors.join('<br />') + '</p>');
        }
    }

    /**
     * エラー消す
     * 
     * @param  {[type]} $parent [description]
     * @return {[type]}         [description]
     */
    function clearFieldError($parent) {
        $parent.find('.ng-alert').remove();
    }
}));