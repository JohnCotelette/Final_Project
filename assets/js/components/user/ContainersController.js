class ContainersController {
    constructor() {
        this.containersControllers = document.getElementsByClassName("controllers");
        this.containers = document.getElementsByClassName("jsContainers");
        this.arrows = document.getElementsByClassName("arrows");
    };

    displayContainers(i) {
        this.containers[i].classList.toggle("reduced");
        this.containers[i].classList.toggle("deployed");

        console.log(this.arrows);

        if (this.arrows[i].dataset.icon === "angle-up") {
            this.arrows[i].dataset.icon = "angle-down";
        }
        else {
            this.arrows[i].dataset.icon = "angle-up";
        }
    };

    initControls() {
        for (let i = 0; i < this.containersControllers.length; i++) {
            this.containersControllers[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.displayContainers(i)
            });
        }
    };
}

let containersController = new ContainersController();
containersController.initControls();