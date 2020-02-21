class FormsController {
    constructor() {
        this.displayFormBusinessController = document.getElementById("changeFormControllerForBusiness");
        this.displayFormUserController = document.getElementById("changeFormControllerForUser");

        this.businessForm = document.getElementById("formBusiness");
        this.userForm = document.getElementById("formProfile");

        this.targetForScroll = document.getElementById("targetForScroll");

        this.inputsCounted = document.getElementsByClassName("textCounted");
        this.counts = document.getElementsByClassName("inputsCount");

        this.inputMaxLength = 5000;
    };

    reset() {
        this.businessForm.classList.remove("fadeOutLeft");
        this.userForm.classList.remove("fadeOutLeft");
        this.businessForm.classList.remove("fadeIn");
        this.userForm.classList.remove("fadeIn");
    };

    displayFormBusiness() {
        sessionStorage.setItem("formState", "business");

        this.reset();

        this.userForm.classList.add("fadeOutLeft");

        setTimeout(() => {
            this.userForm.classList.add("invisible");
            this.businessForm.classList.remove("invisible");
            this.businessForm.classList.add("fadeIn");
        }, 500);

        setTimeout(() => {
            this.scroll(this.targetForScroll);
        }, 550);
    };

    displayFormUser() {
        sessionStorage.setItem("formState", "user");

        this.reset();

        this.businessForm.classList.add("fadeOutLeft");

        setTimeout(() => {
            this.businessForm.classList.add("invisible");
            this.userForm.classList.remove("invisible");
            this.userForm.classList.add("fadeIn");
        }, 500);
    };

    scroll(target) {
        target.scrollIntoView();
    };

    countCarac(i) {
        let count = this.inputsCounted[i].value.length;
        let availableCarac = this.inputMaxLength - count;

        if (availableCarac > 0) {
            this.counts[i].textContent = "Caractères restants : " + availableCarac;
            this.counts[i].style.color = "rgb(51, 51, 51)";
        }
        else if (availableCarac <= 0) {
            this.counts[i].textContent = "Caractères restants : 0";
            this.counts[i].style.color = "red";
        }
    };

    init() {
        if (this.businessForm) {
            for (let i = 0; i < this.inputsCounted.length; i++) {
                this.countCarac(i);
            }

            if (sessionStorage.getItem("formState") === "business") {
                this.businessForm.classList.remove("invisible");
                this.userForm.classList.add("invisible");
            }
        }
    };

    initControls() {
        this.displayFormBusinessController.addEventListener("click", (e) => {
            e.preventDefault();

            this.displayFormBusiness();
        });

        this.displayFormUserController.addEventListener("click", (e) => {
            e.preventDefault();

            this.displayFormUser();
        });

        for (let i = 0; i < this.inputsCounted.length; i++) {
            this.inputsCounted[i].addEventListener("input", () => {
                this.countCarac(i);
            });
        }
    };
}

let formsController = new FormsController();
formsController.initControls();
formsController.init();