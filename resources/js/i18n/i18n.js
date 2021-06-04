;(function(factory) {
    module.exports = factory( 
        require('i18next'),
        require('../i18n/ja.json')
    );
}(function(i18n, langJaJson) {

    return function(lang) {
        // i18n init
        i18n
            .init({
                fallbackLng: lang,
                resources: {
                    ja: {
                        translation: langJaJson
                    }
                }
            });

        return i18n.t;
    }
}));