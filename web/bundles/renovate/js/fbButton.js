window.fbAsyncInit = function() {
    FB.init({
      appId      : '1544454465832076',
      xfbml      : true,
      version    : 'v2.2'
    });
};

(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/uk_UA/sdk.js#xfbml=1&version=v2.2";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

fbHandler = null;
function initFB(){
	clearTimeout(fbHandler);
	setTimeout(function(){
		FB.XFBML.parse();
	}, 1000);
}