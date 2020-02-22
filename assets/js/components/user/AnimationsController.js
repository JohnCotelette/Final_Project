class AnimationsController {
    constructor() {
        this.offersContainers = document.getElementsByClassName("offersContainers");

        this.state = 0;
    };

    animateOffersContainers() {
        let index = 0;

        let intervalID = setInterval(() => {
            this.offersContainers[index].classList.remove("ghost");

            if (this.state === 0) {
                this.state = 1;

                this.offersContainers[index].classList.add("fadeInLeft");
            }
            else {
                this.state = 0;

                this.offersContainers[index].classList.add("fadeInRight");
            }

            if (index === this.offersContainers.length - 1) {
                clearInterval(intervalID);
            }
            else {
                index++;
            }
        }, 250);
    };

    init() {
        if (this.offersContainers) {
            this.animateOffersContainers();
        }
    };
}

let animationsController = new AnimationsController();
animationsController.init();