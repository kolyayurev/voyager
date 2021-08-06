import messages from './messages'


function trans(path) {
    var paths = path.split('.')
        , current = messages
        , i;

    var localePaths = [ ...paths];
    var fallbackPaths = [ ...paths];
    localePaths.unshift(locale);
    fallbackPaths.unshift(fallbackLocale);

    for (i = 0; i < localePaths.length; ++i) {
        if (current[localePaths[i]] == undefined) {
            current = messages
            for (i = 0; i < fallbackPaths.length; ++i) {
                if (current[fallbackPaths[i]] == undefined) {
                    return undefined;
                } else {
                    current = current[fallbackPaths[i]];
                }
            }
            break;
        } else {
            current = current[localePaths[i]];
        }
    }
    return current;
};

Vue.prototype.$t = trans
global.$t = trans