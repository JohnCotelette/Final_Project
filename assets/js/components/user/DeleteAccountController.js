class DeleteAccountController {
    constructor() {
        this.dangerousButton = document.getElementById("dangerousButton");
        this.deleteAccountForm = document.querySelector("#deleteAccountContainer form");
    };

    askConfirm() {
        let dangerousConfirm = window.confirm("Votre compte sera supprimé\nCette action est irréversible\nEtes vous sur de vouloir continuer ?");

        if (dangerousConfirm) {
            this.deleteAccountForm.submit();
        }
    }

    initControls() {
        this.dangerousButton.addEventListener("click", (e) => {
            e.preventDefault();

            this.askConfirm();
        });
    };
}

let deleteAccountController = new DeleteAccountController();
deleteAccountController.initControls();