import ajaxManager from "../../AjaxManager";

class RecruiterPageModalController {
    constructor() {
        this.modalBoxControllers = document.getElementsByClassName("applicationsControllers");
        this.modalBoxContainer = document.getElementById("modalBoxContainer");
        this.closeModalBoxContainerButton = document.getElementById("closeModalBoxContainerButton");

        this.bodyFilter = document.getElementById("bodyFilterDashboard");

        this.ajaxManager = ajaxManager;
    };

    displayModalBoxContainer() {
        this.modalBoxContainer.style.left = "0";
        this.modalBoxContainer.style.opacity = "1";
        this.bodyFilter.classList.remove("invisible");
    };

    hideModalBoxContainer() {
        this.modalBoxContainer.style.left = "100%";
        this.modalBoxContainer.style.opacity = "0.5";
        this.bodyFilter.classList.add("invisible");
    };

    getApplications(offerReference) {
        let url = "/offer/" + offerReference + "/applications";

        this.ajaxManager.getData(url, function(response) {
            console.log(response);
        });
    };

    initControls() {
        for (let i = 0; i < this.modalBoxControllers.length; i++) {
            this.modalBoxControllers[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.getApplications(this.modalBoxControllers[i].getAttribute("data-offerReference"));
                this.displayModalBoxContainer();
            });
        }

        this.bodyFilter.addEventListener("click", this.hideModalBoxContainer.bind(this));

        this.closeModalBoxContainerButton.addEventListener("click", this.hideModalBoxContainer.bind(this));
    };
}

let recruiterPageModalController = new RecruiterPageModalController();
recruiterPageModalController.initControls();