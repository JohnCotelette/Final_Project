class AjaxManager {
    constructor() {};

    getData(url, callback) {
        let req = new XMLHttpRequest();

        req.open("GET", url);

        req.addEventListener("load", () => {
            callback(JSON.parse(req.responseText));
        });

        req.send();
    };

    sendData(url, data, callback, type = null) {
        let req = new XMLHttpRequest();

        req.open("POST", url);

        req.addEventListener("load", () =>
        {
            if (type === "json")
            {
                callback(JSON.parse(req.responseText))
            }
            else
            {
                callback(req.responseText);
            }
        });

        req.send(data);
    };
}

let ajaxManager = new AjaxManager();

export default ajaxManager;