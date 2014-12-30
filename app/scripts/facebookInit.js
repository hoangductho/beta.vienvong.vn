/**
 * Created by hoanggia on 12/4/14.
 */

$(document).ajaxComplete(function(){
    try{
        FB.XFBML.parse();
    }catch(ex){}
});

window.fbAsyncInit = function () {
    FB.init({
        appId: '550251971759267',
        status: true,
        xfbml: true,
        version: 'v2.1'
    });
};
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));