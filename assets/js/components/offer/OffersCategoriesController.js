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
    };

    displayCategoryContainer(i) {
        this.fieldsCategoriesContainers[i].classList.toggle("reduced");
        this.fieldsCategoriesContainers[i].classList.toggle("deployed");

        if (this.arrowsStates[i].dataset.icon === "angle-up") {
            this.arrowsStates[i].dataset.icon = "angle-down";
        }
        else {
            this.arrowsStates[i].dataset.icon = "angle-up";
        }
    };

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
        }, 10);

        this.displayLoader();
        this.categoriesForm.submit();
    }

    displayLoader() {
        console.log(this.loadingCircle);
        this.loadingContainer.classList.remove("invisible");
        this.loadingCircle.classList.remove("invisible");
    }

    initControls() {
        for (let i = 0; i < this.fieldsCategoriesControllers.length; i++) {
            this.fieldsCategoriesControllers[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.displayCategoryContainer(i);
            });
        }

        for (let i = 0; i < this.allCategoriesLinks.length; i++) {
            this.allCategoriesLinks[i].addEventListener("click", () => {
                this.checkRadioButtonForStylingLabels();
            })
        }
    };
}

let offersCategoriesController = new OffersCategoriesController();
offersCategoriesController.initControls();