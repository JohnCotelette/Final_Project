class PublicProfilModalBoxController {
    constructor() {
        this.modalBoxController = document.getElementById("cvController");
        this.modalBoxContainer = document.getElementById("modalBoxContainer");
        this.closeModalBoxContainerButton = document.getElementById("closeModalBoxContainerButton");

        this.bodyFilter = document.getElementById("bodyFilterDashboard");
    };

    displayModalBox() {
        this.modalBoxContainer.style.left = "0";
        this.bodyFilter.classList.remove("invisible");
    };

    hideModalBox() {
        this.modalBoxContainer.style.left = "100%";
        this.bodyFilter.classList.add("invisible");
    };

    initControls() {
        this.modalBoxController.addEventListener("click", this.displayModalBox.bind(this));

        this.closeModalBoxContainerButton.addEventListener("click", this.hideModalBox.bind(this));

        this.bodyFilter.addEventListener("click", this.hideModalBox.bind(this));
    };
}

let publicProfilModalBoxController = new PublicProfilModalBoxController();
publicProfilModalBoxController.initControls();