document.documentElement.classList.add("js");

const subscribeModal = document.querySelector("[data-subscribe-modal]");

if (subscribeModal) {
    const openButtons = document.querySelectorAll("[data-subscribe-open]");
    const closeButtons = subscribeModal.querySelectorAll("[data-subscribe-close]");

    const openModal = () => {
        subscribeModal.hidden = false;
        document.body.style.overflow = "hidden";
    };

    const closeModal = () => {
        subscribeModal.hidden = true;
        document.body.style.overflow = "";
    };

    openButtons.forEach(button => {
        button.addEventListener("click", openModal);
    });

    closeButtons.forEach(button => {
        button.addEventListener("click", closeModal);
    });

    document.addEventListener("keydown", event => {
        if (event.key === "Escape" && !subscribeModal.hidden) {
            closeModal();
        }
    });
}
