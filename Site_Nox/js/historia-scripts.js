// Suaviza a rolagem para a seção features
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.querySelector('.btn-primary');
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('features').scrollIntoView({
                behavior: 'smooth'
            });
        });
    }
    // Animações leves ao rolar (exemplo)
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.about-section, .team-section').forEach(section => {
        observer.observe(section);
    });
});
