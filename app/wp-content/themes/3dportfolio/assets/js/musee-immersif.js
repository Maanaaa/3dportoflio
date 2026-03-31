import * as THREE from 'three';
import { PointerLockControls } from 'three/addons/controls/PointerLockControls.js';

// --- SHADER FEU LIQUIDE ---
const FireShader = {
    uniforms: {
        "iTime": { value: 0 },
        "iResolution": { value: new THREE.Vector2(450, 250) },
        "opacity": { value: 1.0 }
    },
    vertexShader: `
        varying vec2 vUv;
        void main() {
            vUv = uv;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
    `,
    fragmentShader: `
        uniform float iTime;
        uniform vec2 iResolution;
        uniform float opacity;
        varying vec2 vUv;

        float rand(vec2 n) {
            return fract(sin(dot(n, vec2(12.9898,12.1414))) * 83758.5453);
        }
        float noise(vec2 n) {
            const vec2 d = vec2(0.0, 1.0);
            vec2 b = floor(n);
            vec2 f = mix(vec2(0.0), vec2(1.0), fract(n));
            return mix(mix(rand(b), rand(b + d.yx), f.x), mix(rand(b + d.xy), rand(b + d.yy), f.x), f.y);
        }
        vec3 ramp(float t) {
            return t <= .5 ? vec3( 1. - t * 1.4, .2, 1.05 ) / t : vec3( .3 * (1. - t) * 2., .2, 1.05 ) / t;
        }
        float fire(vec2 n) {
            return noise(n) + noise(n * 2.1) * .6 + noise(n * 5.4) * .42;
        }

        void main() {
            float t = iTime;
            vec2 uv = vUv;
            
            float pulse = sin(t * 2.0) * 0.1 + 0.9;
            uv -= 0.5;
            uv *= pulse;
            uv += 0.5;

            uv.x *= iResolution.x / iResolution.y;
            uv.x += uv.y < .5 ? 23.0 + t * .35 : -11.0 + t * .3;    
            uv.y = abs(uv.y - .5);
            uv *= 5.0;
            float q = fire(uv - t * .013) / 2.0;
            vec2 r = vec2(fire(uv + q / 2.0 + t - uv.x - uv.y), fire(uv + q - t));
            float grad = pow((r.y + r.y) * max(.0, uv.y) + .1, 4.0);
            vec3 color = ramp(grad);
            
            color /= (1.20 + max(vec3(0.0), color));
            
            gl_FragColor = vec4(color, opacity);
        }
    `
};

window.addEventListener('DOMContentLoaded', () => {

    const container = document.getElementById('musee-container');
    const instructions = document.getElementById('instructions');
    const startButton = document.getElementById('start-button');
    const projectPopup = document.getElementById('project-popup');
    const closePopupButton = document.getElementById('close-popup');
    const popupIframe = document.getElementById('popup-iframe');
    
    // Nouveaux éléments pour le menu
    const navButtons = document.querySelectorAll('.nav-btn');
    let activeProjectData = null;

    let isPopupOpen = false; 
    let currentHoveredTableau = null;
    
    // Variables pour la transition fluide
    let isTransitioning = false;
    let targetCameraPos = new THREE.Vector3();
    let targetCameraLookAt = new THREE.Vector3();
    let transitionProgress = 0;

    const clock = new THREE.Clock();

    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x020202);

    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 1.7, 5);

    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    container.appendChild(renderer.domElement);

    const controls = new PointerLockControls(camera, renderer.domElement);

    startButton.addEventListener('click', () => controls.lock());

    controls.addEventListener('lock', () => {
        instructions.style.display = 'none';
        projectPopup.style.display = 'none';
        isPopupOpen = false;
        isTransitioning = false;
    });

    controls.addEventListener('unlock', () => {
        if (!isPopupOpen) instructions.style.display = 'block';
    });

    closePopupButton.addEventListener('click', () => {
        isPopupOpen = false;
        projectPopup.style.display = 'none';
        popupIframe.src = ''; 
        popupIframe.style.opacity = "0"; // On cache l'iframe à la fermeture
        controls.lock();
    });

    // Gestion du clic sur les boutons du menu
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (!activeProjectData) return;
            const type = btn.getAttribute('data-type');
            
            let source = "";
            if (type === 'site') source = activeProjectData.lien;
            if (type === 'image') source = activeProjectData.image;
            if (type === 'video') source = activeProjectData.video;

            if(source && source !== "#") {
                popupIframe.src = source;
                popupIframe.style.opacity = "1"; // On affiche l'iframe une fois qu'on a cliqué
            }
        });
    });

    // --- CRÉATION DU GROUPE NÉON (POUR LE HOVER) ---
    const neonGroup = new THREE.Group();
    const neonMat = new THREE.ShaderMaterial({
        uniforms: THREE.UniformsUtils.clone(FireShader.uniforms),
        vertexShader: FireShader.vertexShader,
        fragmentShader: FireShader.fragmentShader,
        transparent: true,
        blending: THREE.AdditiveBlending,
        depthWrite: false
    });

    const epaisseur = 0.08; 
    const largeurTableau = 4.8; 
    const hauteurTableau = 2.8; 

    const geomH = new THREE.BoxGeometry(largeurTableau + epaisseur, epaisseur, epaisseur);
    const bHaut = new THREE.Mesh(geomH, neonMat);
    const bBas = new THREE.Mesh(geomH, neonMat);
    bHaut.position.y = hauteurTableau / 2;
    bBas.position.y = -hauteurTableau / 2;

    const geomV = new THREE.BoxGeometry(epaisseur, hauteurTableau + epaisseur, epaisseur);
    const bGauche = new THREE.Mesh(geomV, neonMat);
    const bDroite = new THREE.Mesh(geomV, neonMat);
    bGauche.position.x = -largeurTableau / 2;
    bDroite.position.x = largeurTableau / 2;

    neonGroup.add(bHaut, bBas, bGauche, bDroite);
    neonGroup.visible = false;
    scene.add(neonGroup);

    // Lumières
    const ambiante = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambiante);

    const hemi = new THREE.HemisphereLight(0xff2e88, 0x000000, 0.4);
    scene.add(hemi);

    const lumierePlafond = new THREE.PointLight(0xff2e88, 2.5, 90);
    lumierePlafond.position.set(0, 8, 0);
    scene.add(lumierePlafond);

    // Sol & murs
    const sol = new THREE.Mesh(
        new THREE.PlaneGeometry(40, 100),
        new THREE.MeshStandardMaterial({ color: 0x080808, roughness: 0.8 })
    );
    sol.rotation.x = -Math.PI / 2;
    sol.receiveShadow = true;
    scene.add(sol);

    const murMat = new THREE.MeshStandardMaterial({ color: 0x111111 });
    const murG = new THREE.Mesh(new THREE.BoxGeometry(1, 12, 100), murMat);
    murG.position.set(-10, 3, 0);
    scene.add(murG);
    const murD = new THREE.Mesh(new THREE.BoxGeometry(1, 12, 100), murMat);
    murD.position.set(10, 3, 0);
    scene.add(murD);

    let listToiles = [];

    fetch('/wp-json/wp/v2/projet?acf_format=standard&per_page=10')
        .then(res => res.json())
        .then(projets => {
            const loader = new THREE.TextureLoader();

            const positions = [
                { x: -9.42, z: -10, rot: Math.PI / 2 },
                { x: 9.42, z: -5, rot: -Math.PI / 2 },
                { x: -9.42, z: 5, rot: Math.PI / 2 },
                { x: 9.42, z: 10, rot: -Math.PI / 2 }
            ];

            projets.forEach((p, i) => {
                if (i >= positions.length) return;
                const pos = positions[i];

                const cadreGeometry = new THREE.BoxGeometry(4.7, 2.7, 0.1);
                const cadreMaterial = new THREE.MeshStandardMaterial({ color: 0x1a1a1a, roughness: 0.5 });
                const cadre = new THREE.Mesh(cadreGeometry, cadreMaterial);
                cadre.position.set(pos.x, 2.2, pos.z);
                cadre.rotation.y = pos.rot;
                scene.add(cadre);

                const toile = new THREE.Mesh(
                    new THREE.PlaneGeometry(4.5, 2.5),
                    new THREE.MeshStandardMaterial({ color: 0xffffff })
                );
                toile.position.set(pos.x, 2.2, pos.z);
                toile.rotation.y = pos.rot;
                toile.position.x += (pos.x < 0) ? 0.16 : -0.16;

                toile.userData = {
                    titre: p.title.rendered,
                    lien: p.acf.lien_du_projet || "#",
                    image: p.acf.image || "#",
                    video: p.acf.video || "#",
                    description: p.acf.description_du_projet || "Pas de description.",
                    objectPos: toile.position.clone(),
                    objectQuat: toile.quaternion.clone()
                };

                scene.add(toile);
                listToiles.push(toile);

                // Spotlights - INTENSITÉ AJUSTÉE À 12 POUR UN MEILLEUR ÉQUILIBRE
                const spot = new THREE.SpotLight(0xff2e88, 12);
                const spotX = (pos.x < 0) ? -6.5 : 6.5;
                spot.position.set(spotX, 6, pos.z);
                spot.target = toile;
                spot.angle = Math.PI / 4.5;
                spot.penumbra = 0;
                spot.decay = 4.5;
                spot.distance = 15;
                scene.add(spot);
                scene.add(spot.target);

                if (p.acf.image_du_projet) {
                    loader.load(p.acf.image_du_projet, (tex) => {
                        toile.material.map = tex;
                        toile.material.needsUpdate = true;
                    });
                }
            });
        });

    const raycaster = new THREE.Raycaster();
    const centreEcran = new THREE.Vector2(0, 0);

    window.addEventListener('click', () => {
        if (!controls.isLocked || isPopupOpen || isTransitioning) return;

        raycaster.setFromCamera(centreEcran, camera);
        const intersections = raycaster.intersectObjects(listToiles);

        if (intersections.length > 0) {
            const object = intersections[0].object;
            const data = object.userData;
            activeProjectData = data; 

            // Préparation de la transition fluide
            const zoomDistance = 3.5;
            const direction = new THREE.Vector3(0, 0, zoomDistance);
            direction.applyQuaternion(object.quaternion); 
            
            targetCameraPos.copy(object.position).add(direction);
            targetCameraLookAt.copy(object.position);
            
            isTransitioning = true;
            transitionProgress = 0;

            setTimeout(() => {
                isPopupOpen = true;
                
                // On vide l'iframe par défaut au clic sur le tableau
                popupIframe.src = '';
                popupIframe.style.opacity = "0";
                
                renderer.render(scene, camera);
                const vector = object.position.clone().project(camera);
                const wH = window.innerWidth / 2, hH = window.innerHeight / 2;
                const pX = (vector.x * wH) + wH, pY = -(vector.y * hH) + hH;
                const fov = camera.fov * (Math.PI / 180);
                const hPX = (2.5 * window.innerHeight) / (2 * Math.tan(fov / 2) * zoomDistance);
                const wPX = hPX * (4.5 / 2.5);

                projectPopup.style.width = `${wPX}px`;
                projectPopup.style.height = `${hPX}px`;
                projectPopup.style.left = `${pX}px`;
                projectPopup.style.top = `${pY}px`;
                projectPopup.style.transform = `translate(-50%, -50%)`;

                const baseWidth = 1280; 
                popupIframe.style.width = `${baseWidth}px`;
                popupIframe.style.height = `${baseWidth * (2.5 / 4.5)}px`;
                popupIframe.style.transform = `scale(${wPX / baseWidth})`;
                popupIframe.style.transformOrigin = "top left";

                projectPopup.style.display = 'block';
                controls.unlock();
            }, 600); 
        }
    });

    let touches = { z: false, s: false, q: false, d: false };
    document.addEventListener('keydown', (e) => touches[e.key.toLowerCase()] = true);
    document.addEventListener('keyup', (e) => touches[e.key.toLowerCase()] = false);

    function animate() {
        requestAnimationFrame(animate);
        const elapsedTime = clock.getElapsedTime();

        if (isTransitioning) {
            transitionProgress += 0.05; 
            camera.position.lerp(targetCameraPos, 0.1);
            
            const currentLookAt = new THREE.Vector3();
            camera.getWorldDirection(currentLookAt);
            const targetDir = targetCameraLookAt.clone().sub(camera.position).normalize();
            const lerpedDir = currentLookAt.lerp(targetDir, 0.1);
            camera.lookAt(camera.position.clone().add(lerpedDir));

            if (transitionProgress >= 1) isTransitioning = false;
        }

        if (controls.isLocked && !isPopupOpen && !isTransitioning) {
            const vitesse = 0.15;
            if (touches.z) controls.moveForward(vitesse);
            if (touches.s) controls.moveForward(-vitesse);
            if (touches.q) controls.moveRight(-vitesse);
            if (touches.d) controls.moveRight(vitesse);

            raycaster.setFromCamera(centreEcran, camera);
            const intersections = raycaster.intersectObjects(listToiles);

            if (intersections.length > 0) {
                const hovered = intersections[0].object;
                if (currentHoveredTableau !== hovered) {
                    currentHoveredTableau = hovered;
                    neonGroup.position.copy(hovered.position);
                    neonGroup.rotation.copy(hovered.rotation);
                    const offset = (hovered.position.x < 0) ? 0.25 : -0.25;
                    neonGroup.position.x += offset;
                    neonGroup.visible = true;
                }
                neonMat.uniforms.iTime.value = elapsedTime;
            } else {
                currentHoveredTableau = null;
                neonGroup.visible = false;
            }
        } else if (isPopupOpen || isTransitioning) {
            neonGroup.visible = false;
        }

        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
});