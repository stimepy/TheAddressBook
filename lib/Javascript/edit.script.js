function deleteEntry() {
    if (confirm("<?php echo $lang['DELETE_CONFIRM'] ?>")) {
        window.location.href = '<?php echo(FILE_SAVE); ?>?id=<?php echo($id); ?>&mode=delete';
    }
}

function deleteAddress(x) {
    document.getElementsByName('address_type_'+x).item(0).value = '';
    document.getElementsByName('address_line1_'+x).item(0).value = '';
    document.getElementsByName('address_line2_'+x).item(0).value = '';
    document.getElementsByName('address_city_'+x).item(0).value = '';
    document.getElementsByName('address_state_'+x).item(0).value = '';
    document.getElementsByName('address_zip_'+x).item(0).value = '';
    document.getElementsByName('address_phone1_'+x).item(0).value = '';
    document.getElementsByName('address_phone2_'+x).item(0).value = '';
    document.getElementsByName('address_country_'+x).item(0).value = '';
}

function saveEntry() {
    document.EditEntry.submit();
}

