<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets | Théo Manya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-display@400,600,700&display=swap">
</head>
<body>

    <canvas id="canvas-three"></canvas>
    <div id="project-overlay">
        <button class="btn-close" onclick="closeProject()">FERMER (ESC)</button>
        <div id="project-container">
            <iframe id="project-iframe" src=""></iframe>
        </div>
    </div>

    <script>
        const WP_API = "<?php echo esc_url_rest(rest_url('wp/v2/projet?_embed')); ?>";
    </script>
</body>
</html>