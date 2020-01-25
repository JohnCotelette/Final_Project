class MobileNav {
    constructor() {
        this.hamburgerButton = document.getElementById("mobileButton");
        this.defaultLinksContainer = document.getElementById("defaultNavLinksContainer");
        this.defaultLinksSeparators = document.getElementsByClassName("defaultNavLinksSeparators");

        this.totalPageContent = document.getElementById("totalPageContent");
    }

    displayMenu() {
        this.defaultLinksContainer.classList.toggle("mobileHidden");
        this.totalPageContent.classList.toggle("blackFilter");
    }

    init() {
        this.defaultLinksSeparators[this.defaultLinksSeparators.length - 1].classList.add("invisible");

        this.initControls();
    }

    initControls() {
        this.hamburgerButton.addEventListener("click", this.displayMenu.bind(this));
    }
}

let mobileNav = new MobileNav;
mobileNav.init();
