class SiretNumberController {
    constructor() {
        this.siretInput = document.getElementById("recruiter_business");
        this.sirenInput = document.getElementById("business_siren");
        this.nicInput = document.getElementById("business_nic");
        this.jsInputs = document.getElementsByClassName("jsInputs");
    };

    isNumber(e) {
        let keyCode = e.which || e.keyCode;

        return keyCode > 47 && keyCode < 58;
    };


    concatValues() {
        this.siretInput.value = this.sirenInput.value + this.nicInput.value;
    };

    initControls() {
        for (let i = 0; i < this.jsInputs.length; i++) {
            this.jsInputs[i].addEventListener("keypress", (e) => {
                let checkNumber = this.isNumber(e);

                if (checkNumber === false) {
                    e.preventDefault()
                }
            });

            this.jsInputs[i].addEventListener("input", this.concatValues.bind(this));
        }
    };
}

let siretNumberController = new SiretNumberController();
siretNumberController.initControls();