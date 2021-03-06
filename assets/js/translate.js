var local = {};
local.en = require('./locale/locale.en_EN');
local.ru = require('./locale/locale.ru_RU');

var messages = {};
messages.ru_RU = local.ru.messages;
messages.en_US = local.en.messages;

if (typeof settings !== 'object') {
    var settings = {};
}
// Default language
settings.lang = 'en_US';

function tr(params, lang) {
    var lang = lang || settings.lang, translated = '', code;
    params = params.toLowerCase().split(':');
    if (messages[lang] !== undefined && params.length) {
        for (var i = 0, msgcat = messages[lang]; i < params.length; i++) {
            code = params[i];
            if (typeof msgcat[code] === 'object') {
                msgcat = msgcat[code];
            }
            if (typeof msgcat[code] === 'string') {
                translated = msgcat[code];
                break;
            }
        }
    }
    return translated;
};

module.exports = {
    trans: tr
};
