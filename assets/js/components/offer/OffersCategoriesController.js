class OffersCategoriesController {
    constructor() {
        this.fieldsCategoriesControllers = document.getElementsByClassName("fieldsCategoriesControllers");
        this.fieldsCategoriesContainers = document.getElementsByClassName("fieldsCategoriesContainers");
        this.arrowsStates = document.getElementsByClassName("arrowsStates");
        this.radioButtons = document.querySelectorAll("#leftSection input[type=radio]");
        this.allCategoriesLinks = document.getElementsByClassName("categoriesLinks");
        this.categoriesForm = document.getElementById("categoriesForm");

        this.loadingContainer = document.getElementById("loading");
        this.loadingCircle = document.getElementById("loadingCircle");

        this.submitCitySearchButton = document.getElementById("submitCitySearch");

        this.categoriesContainerForMobile = document.getElementById("leftSection");
        this.categoriesControllerForMobile = document.getElementById("displayCategoriesForMobiles");
        this.arrowMobile = document.getElementById("arrowMobile");

        this.offersContainers = document.getElementsByClassName("offersContainers");
    };

    displayCategoriesContainer(i) {
        this.fieldsCategoriesContainers[i].classList.toggle("reduced");
        this.fieldsCategoriesContainers[i].classList.toggle("deployed");

        if (this.arrowsStates[i].dataset.icon === "angle-up") {
            this.arrowsStates[i].dataset.icon = "angle-down";
        }
        else {
            this.arrowsStates[i].dataset.icon = "angle-up";
        }
    };

    displayCategoriesContainerForMobile() {
        this.categoriesContainerForMobile.classList.toggle("mobileReduced");

        if (this.arrowMobile.dataset.icon === "angle-up") {
            this.arrowMobile.dataset.icon = "angle-down";
        }
        else {
            this.arrowMobile.dataset.icon = "angle-up";
        }
    }

    checkRadioButtonForStylingLabels() {
        setTimeout(() => {
            for (let i = 0; i < this.radioButtons.length; i++) {
                if (this.radioButtons[i].checked === true) {
                    this.allCategoriesLinks[i].classList.add("linkSelected");
                }
                else {
                    this.allCategoriesLinks[i].classList.remove("linkSelected");
                }
            }

            this.categoriesForm.submit();
        }, 10);

        this.displayLoader();
    }

    displayLoader() {
        console.log(this.loadingCircle);
        this.loadingContainer.classList.remove("invisible");
        this.loadingCircle.classList.remove("invisible");
    }

    animateOffersContainers() {
        let index = 0;

        let intervalID = setInterval(() => {
            this.offersContainers[index].classList.remove("ghost");
            this.offersContainers[index].classList.add("appear");

            if (index === this.offersContainers.length - 1) {
                clearInterval(intervalID);
            }
            else {
                index++;
            }
        }, 150);
    }

    init() {
        for (let i = 0; i < this.radioButtons.length; i++) {
            if (this.radioButtons[i].checked === true) {
                this.allCategoriesLinks[i].classList.add("linkSelected");
            }
            else {
                this.allCategoriesLinks[i].classList.remove("linkSelected");
            }
        }

        this.animateOffersContainers();
    }

    initControls() {
        for (let i = 0; i < this.fieldsCategoriesControllers.length; i++) {
            this.fieldsCategoriesControllers[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.displayCategoriesContainer(i);
            });
        }

        for (let i = 0; i < this.allCategoriesLinks.length; i++) {
            this.allCategoriesLinks[i].addEventListener("click", () => {
                this.checkRadioButtonForStylingLabels();
            })
        }

        this.submitCitySearchButton.addEventListener("click", () => {
            this.displayLoader();
            this.categoriesForm.submit();
        });

        this.categoriesControllerForMobile.addEventListener("click", () => {
            this.displayCategoriesContainerForMobile();
        });
    };
}

let offersCategoriesController = new OffersCategoriesController();
offersCategoriesController.initControls();
offersCategoriesController.init();