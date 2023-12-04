(function(d, s, id) {
    var js, kjs;
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://kaspi.kz/kaspibutton/widget/ks-wi_ext.js';
    kjs = document.getElementsByTagName(s)[0]
    kjs.parentNode.insertBefore(js, kjs);
}(document, 'script', 'KS-Widget'));