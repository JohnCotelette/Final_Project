class OfferAddController {
    constructor() {
        this.checkboxButtons = document.querySelectorAll("#formCategoriesContainer input[type=checkbox]");
        this.allCategoriesLinks = document.querySelectorAll(".categoriesSelectors label");
        this.errorMessage = document.getElementById("categoriesSelectorsError");

        this.offerDescriptionMessage = document.getElementById("offer_description");
        this.offerProfilRequiredMessage = document.getElementById("offer_profilRequired");

        this.descriptionCount = document.getElementById("descriptionCount");
        this.profilRequiredCount = document.getElementById("profilRequiredCount");

        this.categoriesControllerForMobile = document.getElementById("displayCategoriesForMobiles");
        this.categoriesContainerForMobile = document.getElementById("rightSection");

        this.count = 0;
        this.maxLength = 2000;
        this.state = true;
    };

    checkRadioButtonForStylingLabels() {
        setTimeout(() => {
            for (let i = 0; i < this.checkboxButtons.length; i++) {
                if (this.checkboxButtons[i].checked === true) {
                    this.allCategoriesLinks[i].classList.add("linkSelected");
                }
                else {
                    this.allCategoriesLinks[i].classList.remove("linkSelected");
                }
            }

            this.changeState();
        }, 10);
    };

    displayError() {
        if (this.state === true) {
            this.errorMessage.classList.add("invisible");
        }
        else {
            this.errorMessage.classList.remove("invisible");
        }
    };

    changeState() {
        let checkedBoxs = 0;

        for (let i = 0; i < this.checkboxButtons.length; i++) {
            if (this.checkboxButtons[i].checked === true) {
                checkedBoxs++;
            }

            this.count = checkedBoxs;
        }

        if (this.count > 2) {
            this.state = false;
            this.displayError();
        }
        else {
            this.state = true;
            this.displayError();
        }
    };

    countCharactersOfMessage(target) {
        let count = target.value.length;

        let availableTextLength = this.maxLength - count;

        if (target === this.offerDescriptionMessage) {
            if (count >= 100) {
                this.descriptionCount.textContent = "Caractère(s) restants : " + availableTextLength;
                this.descriptionCount.style.color = "rgb(51, 51, 51)";
            }
            else {
                this.descriptionCount.textContent = "Minimum : 100 caractères";
                this.descriptionCount.style.color = "red";
            }
        }
        else {
            if (count >= 80) {
                this.profilRequiredCount.textContent = "Caractère(s) restants : " + availableTextLength;
                this.profilRequiredCount.style.color = "rgb(51, 51, 51)";
            }
            else if(count === this.maxLength) {
                this.descriptionCount.textContent = "Nombre de caractères maximum atteints";
                this.descriptionCount.style.color = "red";
            }
            else {
                this.profilRequiredCount.textContent = "Minimum : 80 caractères";
                this.profilRequiredCount.style.color = "red";
            }
        }
    };

    displayCategoriesContainerForMobile() {
        this.categoriesContainerForMobile.classList.toggle("mobileReduced");
    };

    init() {
        if (this.checkboxButtons) {
            for (let i = 0; i < this.checkboxButtons.length; i++) {
                if (this.checkboxButtons[i].checked === true) {
                    this.allCategoriesLinks[i].classList.add("linkSelected");
                }
                else {
                    this.allCategoriesLinks[i].classList.remove("linkSelected");
                }
            }
        }
    };

    initControls() {
        if (this.allCategoriesLinks) {
            for (let i = 0; i < this.allCategoriesLinks.length; i++) {
                this.allCategoriesLinks[i].addEventListener("click", () => {
                    this.checkRadioButtonForStylingLabels();
                })
            }
        }

        if (this.offerDescriptionMessage) {
            this.offerDescriptionMessage.addEventListener("input", () => {
                this.countCharactersOfMessage(this.offerDescriptionMessage);
            });
        }

        if (this.offerProfilRequiredMessage) {
            this.offerProfilRequiredMessage.addEventListener("input", () => {
                this.countCharactersOfMessage(this.offerProfilRequiredMessage);
            });
        }

        this.categoriesControllerForMobile.addEventListener("click", () => {
            this.displayCategoriesContainerForMobile();
        });
    };
}

let offerAddController = new OfferAddController();
offerAddController.initControls();
offerAddController.init();