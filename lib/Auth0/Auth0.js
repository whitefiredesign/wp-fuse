$(function() {

    var auth0 = new Auth0({
        domain:       Auth0_config['domain'],
        clientID:     Auth0_config['client-id'],
        callbackURL:  Auth0_config['callback-url'],
        responseType: 'code|token'
    });

console.log(Auth0_config['client-id']);

    var login_with_buttons = [
        $('#login-facebook'),
        $('#login-twitter'),
        $('#login-linkedin'),
        $('#login-google')
    ];

    $.each(login_with_buttons, function(k,el) {
        if(el.length>0) {
            el.click(function () {
                auth0.login({
                    connection: $(this).data('connection-name'),
                    popup: true,
                    popupOptions: {
                        width: 450,
                        height: 800
                    }
                });
            });
        }
    });

    
});
