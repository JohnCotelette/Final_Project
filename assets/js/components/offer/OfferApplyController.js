class OfferApplyController {
    constructor() {
        this.applyButton = document.getElementById("applyButton");
        this.closeApplyButton = document.getElementById("applyContainerButton");

        this.offerStatutContainer = document.getElementById("offerStatutContainer");
        this.applyContainer = document.getElementById("applyContainer");

        this.motivationMessage = document.getElementById("apply_motivation");
        this.motivationCountContainer = document.getElementById("motivationCountContainer");
        this.motivationCount = document.getElementById("motivationCount");

        this.state = 0;
        this.count = 0;
        this.maxLength = 1000;
    };

    reset(target) {
        target.classList.remove("disapear");
        target.classList.remove("fadeInRight");
    }

    displayApplyContainer() {
        if (this.state === 0) {
            this.reset(this.applyContainer);

            this.offerStatutContainer.classList.add("disapear");

            setTimeout(() => {
                this.offerStatutContainer.classList.add("invisible");
                this.applyContainer.classList.remove("invisible");
                this.applyContainer.classList.add("fadeInRight");
            }, 350);

            this.state = 1;
        }
    };

    displayOfferStatutContainer() {
        if (this.state === 1) {
            this.reset(this.offerStatutContainer);

            this.applyContainer.classList.add("disapear");

            setTimeout(() => {
                this.applyContainer.classList.add("invisible");
                this.offerStatutContainer.classList.remove("invisible");
                this.offerStatutContainer.classList.add("fadeInRight");
            }, 350);

            this.state = 0;
        }
    }

    countCharactersOfMotivationMessage() {
        this.count = this.motivationMessage.value.length;

        let availableTextLength = this.maxLength - this.count;

        this.motivationCount.textContent = availableTextLength;

        if (availableTextLength === 0) {
            this.motivationCountContainer.style.color = "red";
        }
        else {
            this.motivationCountContainer.style.color = "inherit";
        }
    }

    initControls() {
        if (this.applyButton) {
            this.applyButton.addEventListener("click", this.displayApplyContainer.bind(this));
        }

        if (this.closeApplyButton) {
            this.closeApplyButton.addEventListener("click", this.displayOfferStatutContainer.bind(this));
        }

        if (this.motivationMessage) {
            this.motivationMessage.addEventListener("input", this.countCharactersOfMotivationMessage.bind(this));
        }
    };
}

let offerApplyController = new OfferApplyController();
offerApplyController.initControls();