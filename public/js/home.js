// Wait until the entire DOM is fully loaded before running any JS
document.addEventListener("DOMContentLoaded", () => {

    // Get the container where particles live
    const container = document.getElementById("particles");

    /* =========================================================
       SECTION 1 — SHAPE PARTICLES (Flowing from center)
       ========================================================= */

    function createShapeParticle() {

        // Create a new div element
        const div = document.createElement("div");

        // Add the base particle class (styled in CSS)
        div.classList.add("particle");

        // IMPORTANT:
        // Force starting position to center (behind form)
        div.style.left = "50%";
        div.style.top = "50%";

        // Random size (small subtle particles)
        const size = Math.random() * 8 + 6;
        div.style.width = size + "px";
        div.style.height = size + "px";

        // Random shape: circle or square
        div.style.borderRadius = Math.random() > 0.5 ? "100%" : "0%";

        // Brand colors
        const colors = ["#6E35AD", "#0F0A0F", "#B8ADB1", "#F15F23"];
        div.style.background = colors[Math.floor(Math.random() * colors.length)];

        // Random direction (360 degrees)
        const angle = Math.random() * 2 * Math.PI;

        // Distance they will travel outward
        const distance = Math.random() * 600 + 400;

        // Convert angle + distance into x/y movement
        const x = Math.cos(angle) * distance + "px";
        const y = Math.sin(angle) * distance + "px";

        // Send movement into CSS animation
        div.style.setProperty("--x", x);
        div.style.setProperty("--y", y);

        // Random animation duration
        div.style.animationDuration = (Math.random() * 8 + 6) + "s";

        // Add particle to container
        container.appendChild(div);

        // Remove after animation finishes
        setTimeout(() => div.remove(), 12000);
    }

    // Spawn shape particles
    setInterval(createShapeParticle, 250);


    /* =========================================================
       SECTION 2 — TOOL PARTICLES (Also flowing outward)
       ========================================================= */

    const tools = ["🔨","🛠️","🖌️","✏️","🎨","1️⃣","🎨","💻","📰","✨","✨","🛠️","🔨",];

    function createToolParticle() {

        const div = document.createElement("div");

        // Separate class for emoji styling
        div.classList.add("tool-particle");

        // Set random emoji
        div.textContent = tools[Math.floor(Math.random() * tools.length)];

        // Start from center (behind form)
        div.style.left = "50%";
        div.style.top = "50%";

        // Random emoji size
        div.style.fontSize = (Math.random() * 20 + 16) + "px";

        // Random outward direction
        const angle = Math.random() * 2 * Math.PI;
        const distance = Math.random() * 400 + 300;

        const x = Math.cos(angle) * distance + "px";
        const y = Math.sin(angle) * distance + "px";

        div.style.setProperty("--x", x);
        div.style.setProperty("--y", y);

        // Slightly shorter animation so tools don't dominate
        div.style.animationDuration = (Math.random() * 5 + 4) + "s";

        container.appendChild(div);

        setTimeout(() => div.remove(), 8000);
    }

    // Spawn tools less frequently
    setInterval(createToolParticle, 500);

        /* =========================================================
       SECTION 3 — SMART SHRINKING NAVBAR
       ========================================================= */

    const navbar = document.getElementById("navbar");
    let lastScroll = 0;

    window.addEventListener("scroll", () => {

        const currentScroll = window.pageYOffset;

        if (currentScroll > lastScroll && currentScroll > 10) {
            // scrolling DOWN
            navbar.classList.add("shrink");
        } else {
            // scrolling UP
            navbar.classList.remove("shrink");
        }

        lastScroll = currentScroll;
    });

});

