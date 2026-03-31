<?php get_header(); ?>

<section class="sae-hero">
    <div class="container">
        <span class="badge-tag">SAÉ 4.DWeb-DI.02</span>
        <h1 class="main-title">Musée <span class="text-gradient">Immersif</span></h1>
        <p class="project-pitch">
            Un dispositif interactif 3D propulsé par <strong>Three.js</strong> et piloté par le <strong>CMS WordPress</strong>. 
            Déplacez-vous dans un espace virtuel où chaque œuvre est une donnée dynamique.
        </p>
        <div class="hero-btns">
            <a href="musee/" class="btn-primary">Lancer l'expérience 3D</a>
            <a href="#technique" class="btn-outline">Documentation technique</a>
        </div>
    </div>
</section>

<section id="concept" class="sae-details">
    <div class="container grid-2">
        <div class="detail-text">
            <h2>L'Objectif du Dispositif</h2>
            <p>Concevoir un portfolio où la navigation devient une exploration. Ce musée virtuel permet de :</p>
            <ul class="feature-list">
                <li><i data-lucide="move"></i> Se déplacer librement en vue FPS.</li>
                <li><i data-lucide="lightbulb"></i> Contrôler l'ambiance (Lumières/Couleurs) via l'admin WP.</li>
                <li><i data-lucide="layers"></i> Alimenter les tableaux dynamiquement via ACF.</li>
                <li><i data-lucide="maximize"></i> Interagir avec les œuvres pour ouvrir des vues détaillées.</li>
            </ul>
        </div>
        <div class="detail-stats">
            <div class="stat-card">
                <h3>V1</h3>
                <p>Espace, lumières, alimentation WP & bulles d'interaction.</p>
            </div>
            <div class="stat-card">
                <h3>V2</h3>
                <p>Shaders de lueur, détection d'angle caméra & intégration vidéo.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: L'Architecture Technique (Pour Antony Constantin) -->
<section id="technique" class="sae-tech">
    <div class="container">
        <h2 class="center">Architecture des données</h2>
        <div class="tech-grid">
            <div class="tech-item">
                <i data-lucide="database"></i>
                <h4>Custom Post Types</h4>
                <p>Gestion des "Projets" et des "Réglages 3D" comme entités distinctes.</p>
            </div>
            <div class="tech-item">
                <i data-lucide="settings"></i>
                <h4>ACF & REST API</h4>
                <p>Exposition des champs (couleurs hexadécimales, positions) au format JSON pour Three.js.</p>
            </div>
            <div class="tech-item">
                <i data-lucide="cpu"></i>
                <h4>Fetch API</h4>
                <p>Mise à jour de la scène 3D en temps réel sans rechargement de page.</p>
            </div>
        </div>
    </div>
</section>

<script>lucide.createIcons();</script>
<?php get_footer(); ?>