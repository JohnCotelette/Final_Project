class MobileNav {
    constructor() {
        this.hamburgerButton = document.getElementById("mobileButton");
        this.defaultLinksContainer = document.getElementById("defaultNavLinksContainer");
        this.defaultLinksSeparators = document.getElementsByClassName("defaultNavLinksSeparators");

        this.totalPageContent = document.getElementById("totalPageContent");
        this.mainPageContent = document.getElementsByTagName("main");

        this.state = 0;
    };

    displayMenu() {
        this.defaultLinksContainer.classList.toggle("mobileHidden");
        this.totalPageContent.classList.toggle("blackFilter");

        if (this.state === 0) {
            this.state = 1;
        }
        else {
            this.state = 0;
        }
    };

    init() {
        this.defaultLinksSeparators[this.defaultLinksSeparators.length - 1].classList.add("invisible");

        this.initControls();
    };

    initControls() {
        this.hamburgerButton.addEventListener("click", this.displayMenu.bind(this));

        this.mainPageContent[0].addEventListener("click", () => {
            if (this.state === 1) {
                this.displayMenu();
            }
        });
    };
}

let mobileNav = new MobileNav;
mobileNav.init();
