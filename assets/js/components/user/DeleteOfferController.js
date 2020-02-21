class DeleteOfferController {
    constructor() {
        this.offersContainers = document.getElementsByClassName("offersContainers");
        this.dangerousButtonsDelete = document.getElementsByClassName("dangerousButtonsDelete");
    };

    confirmDelete(i) {
        let confirm = window.confirm("La suppression de cette offre entrainera celle de toutes les candidatures rattachées.\nCette action est irréversible.\nVoulez vous continuer ?");

        if (confirm) {
            this.sendFormDelete(i);
        }
    };

    sendFormDelete(i) {
        let deleteForm = this.offersContainers[i].querySelector("form");

        deleteForm.submit();
    };

    initControls() {
        for (let i = 0; i < this.dangerousButtonsDelete.length; i++) {
            this.dangerousButtonsDelete[i].addEventListener("click", (e) => {
                e.preventDefault();

                this.confirmDelete(i)
            })
        }
    };
}

let deleteOfferController = new DeleteOfferController();
deleteOfferController.initControls();