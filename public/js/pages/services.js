// service info database
const services = {

brandkits:{
title:"Brand Kits & Visual Identity",
text:"We create logos, color palettes, typography systems, and brand guidelines to give your business a strong and consistent identity."
},

web:{
title:"Web Development",
text:"Responsive, fast, SEO-optimized websites built with modern technologies to power your digital presence."
},

apps:{
title:"App Development",
text:"Mobile and web applications designed for performance, scalability, and smooth user experience."
},

software:{
title:"Custom Software",
text:"Tailored software solutions designed to automate processes and solve unique business challenges."
},

support:{
title:"App Maintenance & Support",
text:"Ongoing updates, security improvements, and performance optimization."
},

consult:{
title:"Digital Consultation",
text:"Strategic guidance on technology, product design, and digital growth."
},

api:{
title:"API Development & Integration",
text:"Secure APIs that connect platforms and services seamlessly."
}

}


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