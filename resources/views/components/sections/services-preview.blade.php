<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Services Modal</title>

<style>

 h3 {
    color: #d7b6ff;
 }   
.services-preview {
    padding: 60px 20px;
    font-family: Arial;
}

/* GRID */
.services-preview .services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

/* CARD */
.services-preview .service-card {
    background: #010053;
    padding: 20px;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: 0.3s;
    
}

.services-preview .service-card:hover {
    transform: translateY(-5px);
}

/* MODAL */
.services-preview .services-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.services-preview .services-modal-content {
    background: #ce4e03;
    padding: 25px;
    border-radius: 12px;
    width: 500px;
    max-width: 92%;
    position: relative;
    animation: pop 0.2s ease;
}

@keyframes pop {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.services-preview .services-close {
    position: absolute;
    right: 12px;
    top: 8px;
    font-size: 22px;
    cursor: pointer;
}

.services-preview ul {
    padding-left: 18px;
}
</style>

</head>
<body>

<section class="services-preview">

<div class="services-grid">

<!-- CARD 1 -->
<div class="service-card"
onclick="openModal('Web Development',
`We build high-performance websites designed for speed, scalability and real business growth.

✔ Responsive design for all devices
✔ SEO optimized structure
✔ Modern frameworks like Laravel & React
✔ Fast loading & clean UI`)">

<h3>Web Development</h3>
<p>Modern scalable websites for business growth.</p>
</div>

<!-- CARD 2 -->
<div class="service-card"
onclick="openModal('App Development',
`We create powerful mobile & web applications with smooth UX and strong backend systems.

✔ Android & iOS apps
✔ Full-stack web apps
✔ API integration
✔ Performance optimized architecture`)">

<h3>App Development</h3>
<p>High-performance mobile and web apps.</p>
</div>

<!-- CARD 3 -->
<div class="service-card"
onclick="openModal('Brand Identity',
`We design complete brand systems that make your business memorable and professional.

✔ Logo design
✔ Color palette & typography
✔ Brand guidelines
✔ Consistent visual identity`)">

<h3>Brand Identity</h3>
<p>Strong visual identity & branding systems.</p>
</div>

</div>

<!-- MODAL -->
<div class="services-modal" id="servicesModal" onclick="closeModal(event)">
    <div class="services-modal-content">
        <span class="services-close" onclick="closeModal(event)">×</span>
        <h2 id="servicesTitle"></h2>
        <p id="servicesText" style="white-space: pre-line;"></p>
    </div>
</div>

</section>

<script>
function openModal(title, text) {
    document.getElementById("servicesTitle").innerText = title;
    document.getElementById("servicesText").innerText = text;
    document.getElementById("servicesModal").style.display = "flex";
}

function closeModal(e) {
    if (
        e.target.classList.contains("services-modal") ||
        e.target.classList.contains("services-close")
    ) {
        document.getElementById("servicesModal").style.display = "none";
    }
}
</script>

</body>
</html>