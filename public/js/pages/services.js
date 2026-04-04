// service info database
const services = {

  brandkits: {
    title: "Brand Kits & Visual Identity",
    text: "We craft complete brand identities that make your business recognizable and memorable. This includes logo design, color psychology-based palettes, typography systems, and full brand guidelines. Our goal is to ensure consistency across all platforms — from your website and social media to print and marketing materials — so your brand always looks professional, modern, and trustworthy."
  },

  web: {
    title: "Web Development",
    text: "We build fast, responsive, and SEO-optimized websites using modern frameworks and clean architecture. Every website is designed for performance, scalability, and conversion. From landing pages to full-scale business platforms, we ensure your online presence is powerful, user-friendly, and built to grow your business."
  },

  apps: {
    title: "App Development",
    text: "We develop high-performance mobile and web applications focused on smooth user experience and scalability. Whether it's Android, iOS, or cross-platform solutions, we build apps with strong backend systems, secure APIs, and modern UI/UX design that users actually enjoy using."
  },

  software: {
    title: "Custom Software",
    text: "We design and develop custom software solutions tailored to your business needs. From internal management systems to automation tools, we help streamline operations, reduce manual work, and improve efficiency with scalable and secure architecture."
  },

  support: {
    title: "App Maintenance & Support",
    text: "We provide ongoing maintenance to ensure your applications stay secure, updated, and fully optimized. This includes bug fixes, performance tuning, security patches, and feature updates so your product always runs smoothly without downtime."
  },

  consult: {
    title: "Digital Consultation",
    text: "We guide businesses with strategic digital consulting focused on growth, product planning, and technology decisions. From startup ideas to scaling existing systems, we help you choose the right tools, architecture, and strategies for long-term success."
  },

  api: {
    title: "API Development & Integration",
    text: "We build secure, scalable APIs that connect systems, apps, and services seamlessly. Whether it's third-party integration or custom backend development, we ensure smooth data flow, authentication security, and high-performance communication between platforms."
  }
};


// open modal
function openService(id){

const modal = document.getElementById("service-modal")

document.getElementById("modal-title").innerText = services[id].title
document.getElementById("modal-text").innerText = services[id].text

modal.style.display="flex"

}


// close modal
function closeService(){

document.getElementById("service-modal").style.display="none"

}


// close if clicking background
window.onclick = function(event){

const modal = document.getElementById("service-modal")

if(event.target === modal){
closeService()
}

}


// close with ESC key
document.addEventListener("keydown", function(e){

if(e.key === "Escape"){
closeService()
}

})