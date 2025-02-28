    function updateOpener() {
        window.opener.document.forms[0].pictureURL.value = document.forms[0].pictureURL.value;
        window.close();
    }
