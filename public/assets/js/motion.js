const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

if (!prefersReducedMotion) {
    const revealItems = document.querySelectorAll(".reveal");

    if ("IntersectionObserver" in window && revealItems.length > 0) {
        const observer = new IntersectionObserver(
            entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.16 }
        );

        revealItems.forEach(item => observer.observe(item));
    } else {
        revealItems.forEach(item => item.classList.add("is-visible"));
    }

    const tiltItems = document.querySelectorAll("[data-tilt]");

    tiltItems.forEach(item => {
        item.addEventListener("mousemove", event => {
            const rect = item.getBoundingClientRect();
            const px = (event.clientX - rect.left) / rect.width;
            const py = (event.clientY - rect.top) / rect.height;
            const rotateY = (px - 0.5) * 10;
            const rotateX = (0.5 - py) * 10;

            item.style.transform = `perspective(1200px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
        });

        item.addEventListener("mouseleave", () => {
            item.style.transform = "";
        });
    });
}
