class OffersCategoriesController {
    constructor() {
        this.categoriesControllers = document.getElementsByClassName("categoriesControllers");
        this.categoriesContainers = document.getElementsByClassName("categoriesContainers");
        this.arrowsStates = document.getElementsByClassName("arrowsStates");

        this.categoriesLinks = document.getElementsByClassName("categoriesLinks");

        this.defaultsLinks = document.getElementsByClassName("defaultsLinks")
    };

    displayCategoryContainer(i) {
        this.categoriesContainers[i].classList.toggle("reduced");
        this.categoriesContainers[i].classList.toggle("Sdeployed");

        if (this.arrowsStates[i].dataset.icon === "angle-up") {
            this.arrowsStates[i].dataset.icon = "angle-down";
        }
        else {
            this.arrowsStates[i].dataset.icon = "angle-up";
        }
    };

    attributeFocusOnButton(i) {
        this.categoriesLinks[i].classList.toggle("linkSelected");
    }

    init() {
        for (let i = 0; i < this.defaultsLinks.length; i++) {
            this.defaultsLinks[i].classList.add("linkSelected");
        }
    }

    initControls() {
        for (let i = 0; i < this.categoriesControllers.length; i++) {
            this.categoriesControllers[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.displayCategoryContainer(i);
            });
        }

        for (let i = 0; i < this.categoriesLinks.length; i++) {
            this.categoriesLinks[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.attributeFocusOnButton(i);
            });
        }
    };
}

let offersCategoriesController = new OffersCategoriesController();
offersCategoriesController.init();
offersCategoriesController.initControls();