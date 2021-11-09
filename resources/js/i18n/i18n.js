;(function(factory) {
    module.exports = factory( 
        require('i18next'), 
        require('../i18n/ja.json'),
        require('../i18n/en.json')
    );
}(function(i18n, langJaJson, langEnJson) {

    return function(lang) {
        // i18n init
        i18n
            .init({
                lng: $('html')[0].lang,
                fallbackLng: lang,
                resources: {
                    ja: {
                        translation: langJaJson
                    },
                    en: {
                        translation: langEnJson
                    }
                }
            });

        return i18n.t;
    }
}));