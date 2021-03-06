// Copyright Zikula Foundation 2009 - license GNU/LGPLv3 (or at your option, any later version).

// Create the Zikula.Users object if needed
Zikula.define('Users');

// Create the Zikula.Users.ModifyConfig object
Zikula.Users.AdminNewUser = {
    /**
     * Initializes the scripts and elements on the form.
     */
    init: function()
    {
        $('users_setpass_yes').observe('click', Zikula.Users.AdminNewUser.setpass_onClick);
        $('users_setpass_no').observe('click', Zikula.Users.AdminNewUser.setpass_onClick);
        $('users_setpass_container').removeClassName('z-hide');
        $('users_setpass_no_wrap').removeClassName('z-hide');
        Zikula.Users.AdminNewUser.setpass_onClick();
    },

    /**
     * Click event handler for the setpass field.
     */
    setpass_onClick: function()
    {
        Zikula.radioswitchdisplaystate('users_setpass', 'users_setpass_yes_wrap', true);
        Zikula.radioswitchdisplaystate('users_setpass', 'users_usermustverify_wrap', true);
        Zikula.radioswitchdisplaystate('users_setpass', 'users_setpass_no_wrap', false);
    }
}

// Load and execute the initialization when the DOM is ready. This must be below the definition of the init function!
document.observe("dom:loaded", Zikula.Users.AdminNewUser.init);
