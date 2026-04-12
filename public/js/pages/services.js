document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("service-modal");
    const modalTitle = document.getElementById("modal-title");
    const modalText = document.getElementById("modal-text");
    const closeButton = document.getElementById("modal-close");
    const cards = document.querySelectorAll(".services-page__card");

    if (!modal || !modalTitle || !modalText || !cards.length) {
        return;
    }

    const openModal = (title, description) => {
        modalTitle.textContent = title || "Service";
        modalText.textContent = description || "More details coming soon.";
        modal.classList.add("is-open");
        modal.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";
    };

    const closeModal = () => {
        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
    };

    cards.forEach((card) => {
        const title = card.dataset.title || "Service";
        const description = card.dataset.description || "More details coming soon.";

        card.addEventListener("click", () => {
            openModal(title, description);
        });

        card.addEventListener("keydown", (event) => {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                openModal(title, description);
            }
        });
    });

    closeButton.addEventListener("click", closeModal);

    modal.addEventListener("click", (event) => {
        if (event.target.dataset.close === "true") {
            closeModal();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && modal.classList.contains("is-open")) {
            closeModal();
        }
    });
});
