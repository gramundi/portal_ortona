var box;

window.onload = init;

function init() {
    box = $('idBox');
    box.focus();
    box.onkeyup = monitorKeys;
    var t = setInterval('monitorMode()',2500);
}

function monitorKeys() {
    if(box.value.length < 4) return true;
    else if(box.value.length > 4) {
        box.value = '';
        box.focus();
        return false;
    }
    form = $('idForm');
    $('warning').innerHTML = 'Processing. Please wait...';
    $('instructions').style.display = 'none';
    form.submit();
}

function monitorMode() {
    var element = 'newMode';
    var url = base_url + 'kiosk/checkMode';
    var newMode = new Ajax.Updater(element, url, {method: 'get', asynchronous: false, onSuccess: testMode, onFailure: testMode});
}

function testMode() {
    if($('newMode').innerHTML != currentMode) window.location.reload();
}