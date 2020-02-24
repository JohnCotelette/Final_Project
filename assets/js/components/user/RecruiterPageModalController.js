import ajaxManager from "../../AjaxManager";

class RecruiterPageModalController {
    constructor() {
        this.modalBoxControllers = document.getElementsByClassName("applicationsControllers");
        this.modalBoxContainer = document.getElementById("modalBoxContainer");
        this.closeModalBoxContainerButton = document.getElementById("closeModalBoxContainerButton");
        this.modalContainer = document.getElementById("applicationsContentContainer");

        this.bodyFilter = document.getElementById("bodyFilterDashboard");

        this.ajaxManager = ajaxManager;

        this.animationDelay = 200;
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

    resetModalBoxContent() {
        while (this.modalContainer.firstChild) {
            this.modalContainer.firstChild.remove();
        }
    };

    animateModalBoxContent(blocs) {
        let index = 0;

        let intervalID = setInterval(() => {
            blocs[index].classList.remove("invisible");

            if (index === blocs.length - 1) {
                clearInterval(intervalID);
            }
            else {
                index++;
            }
        }, this.animationDelay);
    };

    fillModalBoxContent(data) {
        for (let i = 0; i < data.length; i++) {
            let newApplicationContainer = document.createElement("div");
            newApplicationContainer.classList.add("applicationContainers");
            newApplicationContainer.classList.add("invisible");

            let newApplicationMotivation = document.createElement("p");
            newApplicationMotivation.textContent = data[i]["motivation"];

            let newApplicationControllerContainer = document.createElement("div");
            newApplicationControllerContainer.classList.add("applicationControllersContainers");

            let newApplicationProfileLink = document.createElement("a");
            newApplicationProfileLink.setAttribute("href", "/profile/" + data[i]["userID"]);
            newApplicationProfileLink.setAttribute("target", "_blank");
            newApplicationProfileLink.textContent = "Profil";

            let newApplicationUserContactLink = document.createElement("a");
            newApplicationUserContactLink.setAttribute("href", "mailto:" + data[i]["userEmail"]);
            newApplicationUserContactLink.textContent = "Contact";

            newApplicationControllerContainer.append(newApplicationProfileLink);
            newApplicationControllerContainer.append(newApplicationUserContactLink);
            newApplicationContainer.append(newApplicationMotivation);
            newApplicationContainer.append(newApplicationControllerContainer);

            if (i < data.length - 1) {
                let newApplicationSeparator = document.createElement("hr");
                newApplicationSeparator.classList.add("modalSeparators");

                newApplicationContainer.append(newApplicationSeparator);
            }

            this.modalContainer.append(newApplicationContainer);
        }

        this.blocsNeedToBeAnimated = document.getElementsByClassName("applicationContainers");

        this.animateModalBoxContent(this.blocsNeedToBeAnimated);
    };

    getApplications(offerReference) {
        let url = "/offer/" + offerReference + "/applications";

        this.ajaxManager.getData(url, (response) => {
            this.fillModalBoxContent(response);
        });
    };

    initControls() {
        for (let i = 0; i < this.modalBoxControllers.length; i++) {
            this.modalBoxControllers[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.resetModalBoxContent();
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