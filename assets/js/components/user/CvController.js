class CvController {
    constructor() {
        this.cvContainerController = document.getElementById("addCvController");

        this.cvHideContainerController = document.getElementById("closeModalBoxContainerButton");
        this.modalBoxContainer = document.getElementById("modalBoxContainer");
        this.bodyFilterDashboard = document.getElementById("bodyFilterDashboard");
        this.cvFormErrorsContainer = document.getElementById("errorCvFormContainer");
        this.cvFormInputFile = document.getElementById("cv_cvFile_file");
        this.cvFormContainerFakeLabel = document.getElementById("fakeLabel");
        this.cvFormSubmitButton = document.getElementById("formCvSubmitButton");
        this.cvForm = document.getElementById("cvForm");

        this.maxSize = 1000000;
        this.exts = ["pdf"];

        this.errorExt = false;
        this.errorSize = false;
    };

    displayChangeCvFormContainer() {
        this.modalBoxContainer.style.left = "0";
        this.bodyFilterDashboard.classList.remove("invisible");
    };

    hideChangeCvFormContainer() {
        this.modalBoxContainer.style.left = "100%";
        this.bodyFilterDashboard.classList.add("invisible");
    };

    changeFakeLabel() {
        if (this.cvFormInputFile.value === '' || this.cvFormInputFile.value == null) {
            this.cvFormContainerFakeLabel.textContent = "Selectionner un fichier...";
            this.cvFormSubmitButton.classList.add("invisible");
        }
        else {
            this.cvFormContainerFakeLabel.textContent = this.cvFormInputFile.value;
            this.cvFormSubmitButton.classList.remove("invisible");
        }
    };

    checkExtAndSizeOfFile(data, file) {
        let currentFileExt = data.split('.').pop();

        let allowed = this.checkOnlyExt(currentFileExt);

        if (file.item(0).size >= this.maxSize) {
            this.errorSize = true;
            allowed = false;
        }
        else {
            this.errorSize = false;
        }

        if (allowed) {
            this.errorExt = false;
            this.errorSize = false;
            this.hideCvFormError();
        }
        else {
            this.displayCvFormError();
        }

        return allowed;
    };

    checkOnlyExt(currentFileExt) {
        let extFinded = null;

        for (let i = 0; i < this.exts.length; i++) {
            if (this.exts[i] === currentFileExt) {
                extFinded = true;
            }
        }

        if (extFinded) {
            this.errorExt = false;

            return true;
        }
        else {
            this.errorExt = true;

            return false;
        }
    }

    displayCvFormError() {
        this.hideCvFormError();

        if (this.errorExt && this.errorSize) {
            this.cvFormErrorsContainer.innerHTML = "Le format du fichier est invalide (autorisé : JPEG/JPG/PNG)<br>Le fichier est trop volumineux (1Mo max)";
        }
        else if (this.errorExt) {
            this.cvFormErrorsContainer.innerHTML = "Le format du fichier est invalide (autorisés : PDF)";
        }
        else if (this.errorSize) {
            this.cvFormErrorsContainer.innerHTML = "Le fichier est trop volumineux (1Mo max)";
        }

        this.cvFormErrorsContainer.classList.remove("invisible");
    }

    hideCvFormError() {
        this.cvFormErrorsContainer.innerHTML = "";
        this.cvFormErrorsContainer.classList.add("invisible");
    }

    initControls() {

        this.cvContainerController.addEventListener("click", this.displayChangeCvFormContainer.bind(this));

        this.cvHideContainerController.addEventListener("click", this.hideChangeCvFormContainer.bind(this));

        this.bodyFilterDashboard.addEventListener("click", this.hideChangeCvFormContainer.bind(this));

        this.cvFormInputFile.addEventListener("input", this.changeFakeLabel.bind(this));

        this.cvFormSubmitButton.addEventListener("click", (e) => {
            e.preventDefault();

            if (this.checkExtAndSizeOfFile(this.cvFormInputFile.value, this.cvFormInputFile.files)) {
                this.cvForm.submit();
            }
        });
    };
}

let cvController = new CvController();
cvController.initControls();