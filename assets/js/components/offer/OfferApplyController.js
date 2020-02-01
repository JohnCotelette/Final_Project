class OfferApplyController {
    constructor() {
        this.applyButton = document.getElementById("applyButton");

        this.offerStatutContainer = document.getElementById("offerStatutContainer");
        this.applyContainer = document.getElementById("applyContainer");

        this.state = 0;
    };

    displayApplyContainer() {
        if (this.state < 1) {
            this.offerStatutContainer.classList.add("disapear");

            setTimeout(() => {
                this.applyContainer.classList.remove("invisible");
                this.applyContainer.classList.add("fadeInRight");
            }, 350);

            this.state = 1;
        }
    };

    initControls() {
        if (this.applyButton) {
            this.applyButton.addEventListener("click", this.displayApplyContainer.bind(this));
        }
    };
}

let offerApplyController = new OfferApplyController();
offerApplyController.initControls();