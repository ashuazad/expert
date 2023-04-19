const getCommon = () => {
    let commonConfig = {};
    let params = (new URL(document.location)).searchParams;
    // get which of SMS api i URL
    commonConfig['url_param_type'] = 'LOGIN_OTP';
    if (params.get('type')) {
        commonConfig.url_param_type = params.get('type');
    }
    return commonConfig;
}

const getDefaultConfig = () => {
    let defaultConfig = {};
    defaultConfig['baseUrl'] = 'https://www.advanceinstitute.co.in';
    defaultConfig['currentTime'] = new Date().getTime();
    return defaultConfig;
}

export {getCommon, getDefaultConfig};