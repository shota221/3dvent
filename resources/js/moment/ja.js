;(function(factory) {
    module.exports = factory(
        require('moment'),
        require('moment/locale/ja')
    );
}(function(moment) {

    moment.locale('ja');

    return moment;
}));