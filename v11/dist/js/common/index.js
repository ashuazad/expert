const getCommon = () => {
    let commonConfig = {};
    let params = (new URL(document.location)).searchParams;
    // get which of SMS api i URL
    commonConfig['comm_api_url_type'] = 'LOGIN_OTP';
    if (params.get('type')) {
        commonConfig.comm_api_url_type = params.get('type');
    }
    return commonConfig;
}

export default getCommon;