class AvatarController {
    constructor() {
        this.avatarContainer = document.getElementById("avatarUser");
        this.changeAvatarContainer = document.getElementById("changeProfilPictureContainer");
        this.changeAvatarButton = document.getElementById("changeAvatarButton");

        this.modalBoxContainer = document.getElementById("modalBoxContainer");
        this.closeModalBoxContainerButton = document.getElementById("closeModalBoxContainerButton");
        this.avatarFormContainerFakeLabel = document.getElementById("fakeLabel");
        this.avatarFormInputFile = document.getElementById("avatar_avatarFile_file");
        this.avatarFormSubmitButton = document.getElementById("formAvatarSubmitButton");
        this.avatarForm = document.querySelector("#modalBoxContent form");
        this.avatarFormErrorsContainer = document.getElementById("errorAvatarFormContainer");

        this.bodyFilterDashboard = document.getElementById("bodyFilterDashboard");

        this.maxSize = 1000000;
        this.exts = ["jpeg", "jpg", "png"];

        this.errorExt = false;
        this.errorSize = false;
    };

    displayChangeAvatarContainer() {
        this.changeAvatarContainer.style.top = "0";
        this.changeAvatarContainer.style.opacity = "1";
    };

    hideChangeAvatarContainer() {
        this.changeAvatarContainer.style.top = "100%";
        this.changeAvatarContainer.style.opacity = "0.5";
    };

    displayChangeAvatarFormContainer() {
        this.modalBoxContainer.style.left = "0";
        this.bodyFilterDashboard.classList.remove("invisible");
    };

    hideChangeAvatarFormContainer() {
        this.modalBoxContainer.style.left = "100%";
        this.bodyFilterDashboard.classList.add("invisible");
    };

    changeFakeLabel() {
        if (this.avatarFormInputFile.value === '' || this.avatarFormInputFile.value == null) {
            this.avatarFormContainerFakeLabel.textContent = "Selectionner un fichier...";
            this.avatarFormSubmitButton.classList.add("invisible");
        }
        else {
            this.avatarFormContainerFakeLabel.textContent = this.avatarFormInputFile.value;
            this.avatarFormSubmitButton.classList.remove("invisible");
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
            this.hideAvatarFormError();
        }
        else {
            this.displayAvatarFormError();
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

    displayAvatarFormError() {
        this.hideAvatarFormError();

        if (this.errorExt && this.errorSize) {
            this.avatarFormErrorsContainer.innerHTML = "Le format du fichier est invalide (autorisés : JPEG/JPG/PNG)<br>Le fichier est trop volumineux (1Mo max)";
        }
        else if (this.errorExt) {
            this.avatarFormErrorsContainer.innerHTML = "Le format du fichier est invalide (autorisés : JPEG/JPG/PNG)";
        }
        else if (this.errorSize) {
            this.avatarFormErrorsContainer.innerHTML = "Le fichier est trop volumineux (1Mo max)";
        }

        this.avatarFormErrorsContainer.classList.remove("invisible");
    }

    hideAvatarFormError() {
        this.avatarFormErrorsContainer.innerHTML = "";
        this.avatarFormErrorsContainer.classList.add("invisible");
    }

    initControls() {
        this.avatarContainer.addEventListener("mouseover", this.displayChangeAvatarContainer.bind(this));
        this.avatarContainer.addEventListener("mouseout", this.hideChangeAvatarContainer.bind(this));

        this.changeAvatarButton.addEventListener("click", (e) => {
            e.preventDefault();

            this.displayChangeAvatarFormContainer();
        });

        this.closeModalBoxContainerButton.addEventListener("click", this.hideChangeAvatarFormContainer.bind(this));
        this.bodyFilterDashboard.addEventListener("click", this.hideChangeAvatarFormContainer.bind(this));

        this.avatarFormInputFile.addEventListener("input", this.changeFakeLabel.bind(this));

        this.avatarFormSubmitButton.addEventListener("click", (e) => {
            e.preventDefault();

            if (this.checkExtAndSizeOfFile(this.avatarFormInputFile.value, this.avatarFormInputFile.files)) {
                this.avatarForm.submit();
            }
        });
    };
}

let avatarController = new AvatarController();
avatarController.initControls();