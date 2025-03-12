async function getFingerprint() {
    return new Promise((resolve) => {
        Fingerprint2.get((components) => {
            const fingerprint = Fingerprint2.x64hash128(
                components.map(pair => pair.value).join(), 31
            );

            resolve(fingerprint);
        });
    });
}

$(document).ready(async function () {
    const pluginIdentifier = $('#yomali-tracker').attr('x-identifier');
    const fingerprint = await getFingerprint();
    const url = $('#yomali-tracker').attr('src').replace('/js/tracker.js', '');

    $.ajax({
        url: url + '/api/receive-tracker',
        method: 'POST',
        data: {
            'plugin-identifier': pluginIdentifier,
            'host': window.location.hostname,
            'url': window.location.pathname,
            'fingerprint': fingerprint
        },
        success(response) {
            console.log('Tracker sent successfully:', response);
        },
        error(e) {
            alert('Something went wrong');
            console.error(e);
        }
    });
});
