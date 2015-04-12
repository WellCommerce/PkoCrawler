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
        page.clipRect = {
            left: 190,
            top: 328,
            width: 532,
            height: 346
        };
        page.render(args[3]);
        phantom.exit();
    }, 2500);
});