var system = require('system');
var page = require('webpage').create();
var args = system.args;

page.onConsoleMessage = function(msg) {
    console.log(msg);
};

page.open("https://www.ipko.pl/", function(status) {

    page.evaluate(function(options) {
        document.querySelector("#client_id").value = options[1];
        document.querySelector("#password").value = options[2];
        document.querySelector("input[name='btn_ok']").click();
    }, args);

    window.setTimeout(function(){
        page.evaluate(function(options) {
            document.querySelector("a[title='Historia rachunku']").click();
        });
    }, 5000);

    window.setTimeout(function(){
        page.evaluate(function(options) {
            document.querySelector("select[name='sel_account']").value = options[4];
            document.querySelector("input[id='beg_date']").value = options[5];
            document.querySelector("input[name='btn_filter']").click();
        }, args);
    }, 7500);

    window.setTimeout(function(){
        page.clipRect = {
            left: 190,
            top: 430,
            width: 850,
            height: 1000
        };

        page.render(args[3]);
        phantom.exit();
    }, 12500);
});