<?php get_header(); ?>

<div id="musee-container">
    
    <div id="instructions" class="musee-popup">
        <h3 style="color: #ff2e88;">Commandes</h3>
        <p><strong>ZQSD</strong> : Déplacement<br><strong>SOURIS</strong> : Vue<br><strong>CLIC</strong> : Voir le projet</p>
        <button id="start-button" class="btn-primary">Entrer</button>
    </div>

    <div id="project-popup" class="musee-popup" style="display: none;">
        <button id="close-popup">×</button>
        
        <!-- Menu de sélection -->
        <div id="popup-menu" style="position: absolute; top: -45px; left: 0; display: flex; gap: 10px; z-index: 10;">
            <button class="nav-btn" data-type="site">SITE</button>
            <button class="nav-btn" data-type="image">IMAGE</button>
            <button class="nav-btn" data-type="video">VIDÉO</button>
        </div>

        <!-- Iframe masquée par défaut (opacity: 0) -->
        <iframe id="popup-iframe" src="" style="width:100%; height:100%; border:none; background: #000; opacity: 0; transition: opacity 0.5s;"></iframe>
    </div>

</div>

<script type="importmap">
  {
    "imports": {
      "three": "https://unpkg.com/three@0.150.1/build/three.module.js",
      "three/addons/": "https://unpkg.com/three@0.150.1/examples/jsm/"
    }
  }
</script>
<script type="module" src="<?php echo get_template_directory_uri(); ?>/assets/js/musee-immersif.js"></script>

<?php get_footer(); ?>