<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nox - Plataforma de Eventos Escolares</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== RESET E VARIÁVEIS ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --dark-bg: #1a1a2e;
            --darker-bg: #16213e;
            --card-bg: #2a3a5e;
            --text-light: #f8f9fa;
            --text-muted: #9ca3af;
            --primary-blue: #3b82f6;
            --hover-blue: #2563eb;
            --accent-yellow: #ffcc00;
            --danger-red: #d9534f;
            --success-green: #28a745;
            --border-color: #374151;
        }

        /* ===== ESTILOS GERAIS ===== */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ===== CABEÇALHO ===== */
        .header {
            background-color: var(--darker-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
        }

        /* Logotipo */
        .logo-container {
            flex-shrink: 0;
        }

        .logo {
            height: 50px;
            width: auto;
            transition: transform 0.3s;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        /* Navegação */
        .nav-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            position: relative;
        }

        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 0;
            transition: all 0.3s;
            display: block;
        }

        .nav-links a.active {
    color: var(--text-light);
}


        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--accent-yellow);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Dropdown do usuário */
        .user-info {
            margin-left: auto;
        }

        .user-dropdown {
            position: relative;
        }

        .user-avatar {
            height: 42px;
            width: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            border-color: var(--primary-blue);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: var(--card-bg);
            min-width: 180px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            padding: 15px;
            z-index: 100;
        }

        .user-dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            color: var(--text-light);
            padding: 8px 0;
            text-decoration: none;
            transition: color 0.3s;
        }

        .dropdown-content a:hover {
            color: var(--primary-blue);
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            height: 80vh;
            min-height: 600px;
            background: linear-gradient(rgba(22, 33, 62, 0.9), rgba(26, 26, 46, 0.9)), url('imagens_de_fundo/hero-bg.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            text-align: center;
            padding-top: 80px;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-content h1 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--text-light);
        }

        .hero-content .lead {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            color: var(--text-muted);
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--hover-blue);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        /* ===== FEATURES SECTION ===== */
        .features-section {
            padding: 80px 0;
            background-color: var(--dark-bg);
        }

        .section-title {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 50px;
            position: relative;
            font-size: 2.2rem;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent-yellow);
            border-radius: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .feature-card {
            background-color: var(--darker-bg);
            border-radius: 10px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-yellow);
            margin-bottom: 20px;
        }

        .feature-card h3 {
            color: var(--text-light);
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .feature-card p {
            color: var(--text-muted);
        }

        /* ===== TEAM SECTION ===== */
        .team-section {
            padding: 80px 0;
            background-color: var(--darker-bg);
        }

        .team-members {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 50px auto 0;
            padding: 0 20px;
        }

        .team-member {
            background-color: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            text-align: center;
            padding-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .member-photo {
    width: 180px;           /* aumenta o tamanho da bolinha */
    height: 180px;
    border-radius: 50%;
    margin: 30px auto 20px; /* centraliza e afasta do topo */
    overflow: hidden;
    border: 5px solid var(--darker-bg);
    position: relative;
    background-color: var(--darker-bg);
    display: flex;
    align-items: center;
    justify-content: center;
}


.member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

        .team-member h3 {
            color: var(--text-light);
            margin-bottom: 5px;
            font-size: 1.3rem;
        }

        .role {
            color: var(--accent-yellow);
            font-weight: 600;
            margin-bottom: 15px;
            display: block;
        }

        .member-bio {
            color: var(--text-muted);
            margin-bottom: 20px;
            padding: 0 20px;
            font-size: 0.95rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--darker-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
        }

        /* ===== FOOTER ===== */
        footer {
            background-color: var(--darker-bg);
            color: var(--text-light);
            padding: 60px 0 30px;
            margin-top: auto;
            border-top: 1px solid var(--border-color);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-about h3 {
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .footer-about p {
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .footer-links h4 {
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-links h4:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--accent-yellow);
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: var(--text-muted);
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-blue);
            padding-left: 5px;
        }

        .footer-bottom {
            border-top: 1px solid var(--border-color);
            padding-top: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .copyright {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .footer-social {
            display: flex;
            gap: 15px;
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            background-color: var(--primary-blue);
            transform: translateY(-3px);
        }

        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            .nav-container {
                position: static;
                transform: none;
                order: 2;
                width: 100%;
            }

            .nav-links {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }

            .logo-container {
                order: 1;
            }

            .user-info {
                order: 3;
                margin: 15px 0 0;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .hero-content .lead {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .team-member {
                max-width: 350px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <!-- Logo -->
            <div class="logo-container">
                <a href="index.php" aria-label="Página inicial">
                    <img src="imagem_logotipo/logo.png" alt="Logotipo Nox" class="logo">
                </a>
            </div>

            <!-- Menu de Navegação -->
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="index.php">Inicial</a></li>
                    <li><a href="historia.php" class="active">Sobre Nós</a></li>
                    <li><a href="eventos.php">Eventos</a></li>
                </ul>
            </div>

            <!-- Área do Usuário -->
            <div class="user-info">
                <div class="user-dropdown">
                    <img src="<?= isset($_SESSION['imagem']) ? $_SESSION['imagem'] : 'imagem_logotipo/usuario_padrao.png' ?>" 
                         alt="Foto do usuário" class="user-avatar">
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <p><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['usuario']['nome']) ?></p>
                            <p><strong>Tipo:</strong> <?= htmlspecialchars($_SESSION['usuario']['tipo']) ?></p>
                            <a href="php/logout.php" class="logout-btn">Sair</a>
                            <form action="php/upload_imagem.php" method="post" enctype="multipart/form-data">
                                <label for="nova_imagem">Trocar imagem:</label>
                                <input type="file" name="nova_imagem" id="nova_imagem" accept="image/*">
                                <button type="submit">Atualizar</button>
                            </form>
                        <?php else: ?>
                            <a href="login.php">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <!-- Hero Section -->
     <!-- Seção Hero -->

    <section class="hero-section">
        <div class="hero-content">
            <h1>Sobre a Plataforma Nox</h1>
            <p class="lead">Conheça a ferramenta que está transformando a gestão de eventos escolares em todo o país</p>
            <a href="#features" class="btn btn-primary">Nossos Recursos</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <h2 class="section-title">Nossa Tecnologia</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Gestão Inteligente</h3>
                <p>Controle completo de eventos com ferramentas intuitivas que simplificam a organização escolar.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Comunicação Integrada</h3>
                <p>Mantenha todos informados com nosso sistema de notificações e avisos em tempo real.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Análise de Dados</h3>
                <p>Relatórios detalhados para acompanhar o sucesso dos seus eventos e tomar decisões estratégicas.</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
<section class="team-section">
        <h2 class="section-title">Nossa Equipe</h2>
        
        <div class="team-members">
            <!-- Leandro -->
            <div class="team-member">
                <div class="member-photo">
                    <img src="perfil_imagens/membro1.jpeg" alt="Leandro - Full Stack Developer" style="width:100%;height:auto;">
                </div>
                <h3>Leandro da Silva</h3>
                <span class="role">Full Stack Developer</span>
                <p class="member-bio">Responsável pela arquitetura e desenvolvimento da plataforma.</p>
                <div class="social-links">
                    <a href="https://github.com/leandro" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    <a href="https://linkedin.com/in/leandro" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <!-- João -->
            <div class="team-member">
                <div class="member-photo">
                    <img src="perfil_imagens/membro2.jpeg" alt="João - UI/UX Designer" style="width:100%;height:auto;">
                </div>
                <h3>João Vitor</h3>
                <span class="role">UI/UX Designer</span>
                <p class="member-bio">Especialista em experiência do usuário, cria interfaces intuitivas que facilitam a vida de professores e alunos.</p>
                <div class="social-links">
                    <a href="https://github.com/joao" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    <a href="https://linkedin.com/in/joao" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <!-- Victor -->
            <div class="team-member">
                <div class="member-photo">
                    <img src="perfil_imagens/membro3.jpeg" alt="Victor - Mobile Developer" style="width:100%;height:auto;">
                </div>
                <h3>Victor Thales</h3>
                <span class="role">Mobile Developer</span>
                <p class="member-bio">Desenvolvedor especializado em aplicativos educacionais, garantindo acesso em qualquer dispositivo.</p>
                <div class="social-links">
                    <a href="https://github.com/victor" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    <a href="https://linkedin.com/in/victor" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container" style="max-width: 1200px; margin: 0 auto; padding: 30px 20px 20px; background-color: var(--darker-bg); text-align: left;">
            <!-- Seção "Sobre o Nox" -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: var(--text-light); margin-bottom: 15px; font-size: 1.1rem;">Sobre o Nox</h3>
                <p style="color: var(--text-muted); margin-bottom: 20px; max-width: 600px;">
                    Plataforma de organização de eventos escolares para professores e alunos.
                </p>
                
                <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                    <div>
                        <h4 style="color: var(--text-light); font-size: 0.95rem; margin-bottom: 10px;">Eventos</h4>
                        <a href="eventos.php" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none;">Ver eventos disponíveis</a>
                    </div>
                    <div>
                        <h4 style="color: var(--text-light); font-size: 0.95rem; margin-bottom: 10px;">Contato</h4>
                        <a href="mailto:suporte@nox.com" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none;">suporte@nox.com</a>
                    </div>
                </div>
            </div>

            <hr style="border: none; height: 1px; background-color: var(--border-color); margin: 20px 0;">

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                <div style="display: flex; flex-wrap: wrap; gap: 15px 25px; margin-right: auto;">
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Português (BR)</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Privacidade</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Termos</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Cookies</a>
                </div>
                
                <div style="display: flex; align-items: center; gap: 20px; margin-left: auto;">
                    <span style="color: var(--text-muted); font-size: 0.85rem;">
                        © 2025 Nox. Todos os direitos reservados.
                    </span>
                    
                    <div class="social-icons" style="display: flex; gap: 15px;">
                        <a href="#" style="display: inline-flex; width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.1); align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <img src="imagens_rede/instagram.svg" alt="Instagram" style="height: 16px; filter: brightness(0.8); transition: all 0.3s ease;">
                        </a>
                        <a href="#" style="display: inline-flex; width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.1); align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <img src="imagens_rede/facebook.svg" alt="Facebook" style="height: 16px; filter: brightness(0.8); transition: all 0.3s ease;">
                        </a>
                        <a href="#" style="display: inline-flex; width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.1); align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <img src="imagens_rede/whatsapp.svg" alt="WhatsApp" style="height: 16px; filter: brightness(0.8); transition: all 0.3s ease;">
                        </a>
                    </div>
                </div>
            </div>
    </div>

    <script>
        // Efeito de scroll suave
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Header fixo e com efeito de scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.3)';
            } else {
                header.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>