// Smooth scroll on anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e){
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Optional: Particle.js effect
if(document.getElementById('portfolio-particles')){
    particlesJS('portfolio-particles', {
        particles: {
            number: { value: 60 },
            size: { value: 3 },
            color: { value: '#ffffff' },
            line_linked: { enable: true, distance: 150, color: '#ffffff', opacity: 0.4 },
            move: { speed: 2 }
        }
    });
}