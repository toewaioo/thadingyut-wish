<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thadingyut Lanterns</title>
  <style>
    body {
      margin: 0;
      overflow: hidden;
      font-family: "Poppins", sans-serif;
      color: white;
      background: #000;
    }

    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");

    canvas {
      display: block;
    }

    .ui {
      position: fixed;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 100;
      display: flex;
      gap: 15px;
    }

    .ui button {
      padding: 10px 10px;
      font-size: 0.9rem;
      cursor: pointer;
      border: none;
      border-radius: 50px;
      color: white;
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
      transition: transform 0.2s, background-color 0.3s;
    }

    #wish-button {
      background: linear-gradient(45deg, #ff7e5f, #feb47b);
    }

    #wish-button:hover {
      transform: translateY(-3px);
      background: linear-gradient(45deg, #e66a4a, #e49f69);
    }

    #zoom-in-button,
    #zoom-out-button {
      background: linear-gradient(45deg, #4caf50, #81c784);
    }

    #zoom-in-button:hover,
    #zoom-out-button:hover {
      transform: translateY(-3px);
      background: linear-gradient(45deg, #45a049, #74b878);
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 200;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(5px);
      justify-content: center;
      align-items: center;
      animation: fadeIn 0.3s ease-in-out forwards;
    }

    .modal-content {
      background: #1a1a1a;
      padding: 40px;
      border-radius: 15px;
      position: relative;
      width: 90%;
      max-width: 550px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.7);
      text-align: center;
      border: 1px solid #333;
      animation: slideIn 0.4s ease-out forwards;
    }

    .close-button {
      position: absolute;
      top: 15px;
      right: 25px;
      color: #999;
      font-size: 32px;
      font-weight: bold;
      cursor: pointer;
      transition: color 0.2s;
    }

    .close-button:hover {
      color: white;
    }

    textarea {
      width: 100%;
      height: 180px;
      padding: 15px;
      margin-top: 15px;
      margin-bottom: 25px;
      border-radius: 8px;
      border: 1px solid #444;
      background: #2c2c2c;
      color: white;
      box-sizing: border-box;
      resize: none;
      font-size: 1rem;
    }

    textarea::placeholder {
      color: #aaa;
    }

    #submit-wish {
      padding: 12px 25px;
      font-size: 1.1rem;
      background: linear-gradient(45deg, #2196f3, #2979ff);
      color: white;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
      transition: transform 0.2s, background-color 0.3s;
    }

    #submit-wish:hover {
      transform: translateY(-2px);
      background: linear-gradient(45deg, #1e88e5, #266dd1);
    }

    #display-wish-message {
      font-size: 1.4rem;
      line-height: 1.6;
      color: #e0e0e0;
    }

    #display-wish-author {
      margin-top: 10px;
      font-style: italic;
      color: #999;
    }

    #scene-container {
      width: 100vw;
      height: 100vh;
    }

    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #000;
      z-index: 1000;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 2rem;
    }

    .color-swatches {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    .color-swatch {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      cursor: pointer;
      border: 2px solid transparent;
      transition: border-color 0.2s;
    }

    .color-swatch.selected {
      border-color: white;
    }

    .author-info {
      margin-bottom: 15px;
    }

    .author-info input[type="text"] {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #555;
      background: #444;
      color: white;
      box-sizing: border-box;
    }

    .author-info label {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      margin-top: 10px;
    }

    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes slideIn {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tweenjs/tween.js@18.6.4/dist/tween.umd.js"></script>

  <div id="loading-overlay" class="loading-overlay">Loading...</div>

  <div id="scene-container"></div>
  <div class="ui">
    <button id="wish-button">ဆုတောင်းစကားတစ်ခုလွတ်တင်မယ်</button>
  </div>

  <div id="wish-modal" class="modal">
    <div class="modal-content">
      <span class="close-button">&times;</span>
      <h2>ဆုတောင်းစာရေးပါ</h2>
      <div class="color-swatches">
        <div
          class="color-swatch selected"
          data-color="#ff9800"
          style="background-color: #ff9800"></div>
        <div
          class="color-swatch"
          data-color="#87ceeb"
          style="background-color: #87ceeb"></div>
        <div
          class="color-swatch"
          data-color="#4caf50"
          style="background-color: #4caf50"></div>
        <div
          class="color-swatch"
          data-color="#ee82ee"
          style="background-color: #ee82ee"></div>
        <div
          class="color-swatch"
          data-color="#ffd700"
          style="background-color: #ffd700"></div>
      </div>
      <textarea
        id="wish-text"
        placeholder="သင့်ဆုတောင်းကိုဒီမှာရေးပါ..."></textarea>
      <div class="author-info">
        <input type="text" id="author-name" placeholder="သင့်နာမည်ရေးပါ" />
        <label>
          <input type="checkbox" id="anonymous-checkbox" />
          အမည်မဖော်လိုပါ
        </label>
      </div>
      <button id="submit-wish">ဆုတောင်းလွတ်တင်မယ်</button>
    </div>
  </div>

  <div id="display-wish-modal" class="modal">
    <div class="modal-content">
      <span class="close-button">&times;</span>
      <p id="display-wish-message"></p>
      <p id="display-wish-author"></p>
    </div>
  </div>

  <script>
    let scene, camera, renderer, controls, raycaster, pointerStart;
    let lanterns = [];
    const MAX_LANTERNS = 500;
    let cameraAnimating = false;
    let flameTexture;
    let originalCameraPosition, originalControlsTarget;

    const lanternColors = {
      "#ff9800": 0xffa500,
      "#87ceeb": 0x87ceeb,
      "#ee82ee": 0xee82ee,
      "#ffd700": 0xffd700,
      "#ff0000": 0xff0000,
      "#00ff00": 0x00ff00,
    };
    let selectedColor = 0xffa500;

    // Function to convert hex string (e.g., #FF9800) to hex integer (e.g., 0xFF9800)
    function hexToInt(hex) {
      return parseInt(hex.replace("#", "0x"), 16);
    }

    // --- CORE INITIALIZATION ---
    function init() {
      const loadingOverlay = document.getElementById("loading-overlay");
      const textureLoader = new THREE.TextureLoader();
      textureLoader.load(
        "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9e2Bhr3a62h92n1WqWqsVSKNO1qtgvPsODQ&amp;s",
        (texture) => {
          flameTexture = texture;
          loadingOverlay.style.display = "none";
          setupScene();
        }
      );
    }

    async function setupScene() {
      scene = new THREE.Scene();
      camera = new THREE.PerspectiveCamera(
        100,
        window.innerWidth / window.innerHeight,
        0.1,
        1000
      );
      camera.position.set(0, 20, 100);

      renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true
      });
      renderer.setSize(window.innerWidth, window.innerHeight);
      renderer.setPixelRatio(window.devicePixelRatio);
      document
        .getElementById("scene-container")
        .appendChild(renderer.domElement);

      controls = new THREE.OrbitControls(camera, renderer.domElement);
      controls.enableDamping = true;
      controls.dampingFactor = 0.07;
      controls.enablePan = false;
      controls.minDistance = 1;
      controls.maxDistance = 500;
      controls.maxPolarAngle = Math.PI / 1 + 0.1;

      raycaster = new THREE.Raycaster();
      pointerStart = new THREE.Vector2();

      addLights();
      createGround();
      createMoon();
      createStarfield();

      // --- FETCH INITIAL WISHES FROM PHP/MYSQL ---
      try {
        const response = await fetch("api.php", {
          method: "GET",
        });
        const initialWishes = await response.json();

        // Limit to MAX_LANTERNS, taking the most recent ones (due to ORDER BY created_at ASC in PHP)
        const startCount =
          initialWishes.length > MAX_LANTERNS ?
          initialWishes.length - MAX_LANTERNS :
          0;

        for (let i = startCount; i < initialWishes.length; i++) {
          const wishData = initialWishes[i];
          // PHP returns color_code as integer, so we pass it directly
          createLantern(
            wishData.wish_message,
            wishData.author_name,
            wishData.color_code
          );
        }
      } catch (error) {
        console.error("Error fetching initial wishes:", error);
        // Fallback: If DB fails, create a few default lanterns without specific data
        for (let i = 0; i < 5; i++) {
          createLantern(
            "Database connection failed. Showing default wish.",
            "System",
            0xff0000
          );
        }
      }

      renderer.domElement.addEventListener(
        "pointerdown",
        onPointerDown,
        false
      );
      renderer.domElement.addEventListener("pointerup", onPointerUp, false);
      window.addEventListener("resize", onWindowResize, false);

      setupModal();
      animate();
    }

    function addLights() {
      scene.add(new THREE.HemisphereLight(0xffffff, 0x080820, 1));
      scene.add(new THREE.AmbientLight(0xffffff, 0.3));
    }

    function createGround() {
      const groundGeo = new THREE.PlaneGeometry(1000, 1000);
      const groundMat = new THREE.MeshPhongMaterial({
        color: 0x111111,
        specular: 0x000000,
        shininess: 0,
      });
      const ground = new THREE.Mesh(groundGeo, groundMat);
      ground.rotation.x = -Math.PI / 2;
      ground.position.y = -50;
      scene.add(ground);
    }

    function createMoon() {
      const moonGeo = new THREE.SphereGeometry(20, 32, 32);
      const moonMat = new THREE.MeshBasicMaterial({
        color: 0xffd700,
        emissive: 0xffd700,
        emissiveIntensity: 0.5,
      });
      const moon = new THREE.Mesh(moonGeo, moonMat);
      moon.position.set(150, 150, -200);
      scene.add(moon);
    }

    function createStarfield() {
      const starGeometry = new THREE.BufferGeometry();
      const starVertices = [];
      for (let i = 0; i < 5000; i++) {
        const x = THREE.MathUtils.randFloatSpread(2000);
        const y = THREE.MathUtils.randFloatSpread(2000);
        const z = THREE.MathUtils.randFloatSpread(2000);
        starVertices.push(x, y, z);
      }
      starGeometry.setAttribute(
        "position",
        new THREE.Float32BufferAttribute(starVertices, 3)
      );
      const starMaterial = new THREE.PointsMaterial({
        color: 0xeeeeee,
        size: 0.8,
      });
      const stars = new THREE.Points(starGeometry, starMaterial);
      scene.add(stars);
    }

    function createLantern(
      wishMessage,
      authorName,
      colorCode,
      isNew = false
    ) {
      const group = new THREE.Group();
      const lanternColor = new THREE.Color(colorCode);
      const lanternSize = THREE.MathUtils.randFloat(1.2, 2.0);

      const lanternGeo = new THREE.SphereGeometry(
        lanternSize,
        32,
        16,
        0,
        Math.PI * 2,
        0,
        Math.PI * 0.5
      );
      lanternGeo.scale(1, 1.5, 1);
      const lanternMat = new THREE.MeshPhongMaterial({
        color: lanternColor,
        transparent: true,
        opacity: 0.8,
        side: THREE.DoubleSide,
        emissive: lanternColor,
        emissiveIntensity: 0.8,
        specular: 0x111111,
        shininess: 30,
      });
      const lanternBody = new THREE.Mesh(lanternGeo, lanternMat);
      group.add(lanternBody);

      const capMat = new THREE.MeshStandardMaterial({
        color: 0x8b4513,
        metalness: 0.5,
        roughness: 0.8,
      });
      const topGeo = new THREE.CylinderGeometry(
        lanternSize * 0.8,
        lanternSize,
        0.5,
        16
      );
      const top = new THREE.Mesh(topGeo, capMat);
      top.position.y = lanternSize * 1.5 + 0.25;
      group.add(top);

      const bottomGeo = new THREE.CylinderGeometry(
        lanternSize * 0.6,
        lanternSize * 0.8,
        0.5,
        16
      );
      const bottom = new THREE.Mesh(bottomGeo, capMat);
      bottom.position.y = -lanternSize * 1.5 - 0.25;
      group.add(bottom);

      const flameGeometry = new THREE.BufferGeometry();
      const flameVertices = [];
      const flameColors = [];
      const sizes = [];
      for (let i = 0; i < 20; i++) {
        flameVertices.push(0, 0, 0);
        flameColors.push(1, 0.5, 0.1);
        sizes.push(THREE.MathUtils.randFloat(0.5, 1.5));
      }
      flameGeometry.setAttribute(
        "position",
        new THREE.Float32BufferAttribute(flameVertices, 3)
      );
      flameGeometry.setAttribute(
        "color",
        new THREE.Float32BufferAttribute(flameColors, 3)
      );
      flameGeometry.setAttribute(
        "size",
        new THREE.Float32BufferAttribute(sizes, 1)
      );

      const flameMaterial = new THREE.PointsMaterial({
        size: 1,
        map: flameTexture,
        blending: THREE.AdditiveBlending,
        transparent: true,
        vertexColors: true,
        sizeAttenuation: true,
      });
      const flame = new THREE.Points(flameGeometry, flameMaterial);
      flame.position.y = -1.5 * lanternSize;
      group.add(flame);

      const flameParticles = {
        positions: flameGeometry.attributes.position,
        velocities: new Array(20)
          .fill(null)
          .map(
            () =>
            new THREE.Vector3(
              THREE.MathUtils.randFloatSpread(0.1),
              THREE.MathUtils.randFloat(0.01, 0.05),
              THREE.MathUtils.randFloatSpread(0.1)
            )
          ),
        lifetimes: new Array(20).fill(0).map(() => Math.random() * 5 + 5),
        currentLifetimes: new Array(20).fill(0),
        colors: flameGeometry.attributes.color,
        sizes: flameGeometry.attributes.size,
        initialSizes: Array.from(flameGeometry.attributes.size.array),
      };

      const lantern = {
        group,
        wish: wishMessage,
        author: authorName,
        speed: THREE.MathUtils.randFloat(0.05, 0.15),
        swayMagnitude: THREE.MathUtils.randFloat(2, 6),
        swaySpeed: THREE.MathUtils.randFloat(0.005, 0.02),
        time: Math.random() * 100,
        flameParticles,
      };

      // All lanterns start from the ground
      lantern.group.position.set(
        THREE.MathUtils.randFloatSpread(200),
        -50,
        THREE.MathUtils.randFloatSpread(200)
      );

      scene.add(lantern.group);
      lanterns.push(lantern);
    }

    async function createNewLantern(message, author, colorHex) {
      // Send wish to database via API
      try {
        const response = await fetch("api.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            wish_message: message,
            author_name: author,
            color_code: colorHex, // Send hex string, PHP converts it
          }),
        });

        if (!response.ok) {
          const errorData = await response.json();
          alert(`Failed to save wish: ${errorData.error || "Server error"}`);
          return;
        }
      } catch (error) {
        console.error("Network or server issue:", error);
        alert(
          "Cannot connect to the wish server. Please check your PHP/MySQL setup."
        );
        return;
      }

      // Client-side lantern creation logic
      if (lanterns.length >= MAX_LANTERNS) {
        const lanternToRemove = lanterns.shift();
        scene.remove(lanternToRemove.group);
      }

      // Create the new lantern using the *integer* color code (which is stored in selectedColor)
      createLantern(message, author, selectedColor, true);

      animateCameraToLantern(lanterns[lanterns.length - 1]);
    }

    function animateCameraToLantern(newLantern) {
      cameraAnimating = true;
      controls.enabled = false;

      originalCameraPosition = camera.position.clone();
      originalControlsTarget = controls.target.clone();

      const duration = 3000;

      new TWEEN.Tween({
          t: 0
        })
        .to({
          t: 1
        }, duration)
        .easing(TWEEN.Easing.Quadratic.InOut)
        .onUpdate(({
          t
        }) => {
          const lanternPos = newLantern.group.position;
          const targetCameraPos = originalCameraPosition
            .clone()
            .add(new THREE.Vector3(0, 15, 20)); // Define end position relative to original

          camera.position.lerpVectors(
            originalCameraPosition,
            targetCameraPos,
            t
          );
          controls.target.lerpVectors(originalControlsTarget, lanternPos, t);
          controls.update();
        })
        .onComplete(() => {
          new TWEEN.Tween({
              t: 0
            })
            .to({
              t: 1
            }, 1500)
            .easing(TWEEN.Easing.Quadratic.InOut)
            .onUpdate(({
              t
            }) => {
              camera.position.lerpVectors(
                camera.position,
                originalCameraPosition,
                t
              );
              controls.target.lerpVectors(
                controls.target,
                originalControlsTarget,
                t
              );
              controls.update();
            })
            .onComplete(() => {
              cameraAnimating = false;
              controls.enabled = true;
            })
            .start();
        })
        .start();
    }

    function setupModal() {
      const wishButton = document.getElementById("wish-button");
      const wishModal = document.getElementById("wish-modal");
      const displayWishModal = document.getElementById("display-wish-modal");
      const closeButtons = document.querySelectorAll(".close-button");
      const submitWishBtn = document.getElementById("submit-wish");
      const wishTextarea = document.getElementById("wish-text");
      const colorSwatches = document.querySelectorAll(".color-swatch");
      const authorNameInput = document.getElementById("author-name");
      const anonymousCheckbox = document.getElementById("anonymous-checkbox");

      // Initialize default selected color
      let selectedColorHex = "#ff9800";

      colorSwatches.forEach((swatch) => {
        swatch.addEventListener("click", () => {
          colorSwatches.forEach((s) => s.classList.remove("selected"));
          swatch.classList.add("selected");
          selectedColorHex = swatch.dataset.color;
          selectedColor = hexToInt(selectedColorHex);
        });
      });

      wishButton.onclick = (event) => {
        event.stopPropagation();
        if (!cameraAnimating) {
          wishModal.style.display = "flex";
        }
      };

      submitWishBtn.onclick = () => {
        const message = wishTextarea.value.trim();
        let authorName = authorNameInput.value.trim();
        const isAnonymous = anonymousCheckbox.checked;

        if (message) {
          if (isAnonymous || authorName === "") {
            authorName = "Anonymous";
          }

          // Pass the HEX string to the creation function for sending to PHP
          createNewLantern(message, authorName, selectedColorHex);

          wishTextarea.value = "";
          authorNameInput.value = "";
          anonymousCheckbox.checked = false;

          // Reset selected color UI to default orange
          selectedColor = 0xffa500;
          selectedColorHex = "#ff9800";
          colorSwatches.forEach((s) => s.classList.remove("selected"));
          document
            .querySelector('[data-color="#ff9800"]')
            .classList.add("selected");

          wishModal.style.display = "none";
        } else {
          alert("ဆုတောင်းစာရေးဖို့လိုပါတယ်!");
        }
      };

      closeButtons.forEach((btn) => {
        btn.onclick = (event) => {
          const modal = event.target.closest(".modal");
          if (modal) modal.style.display = "none";
        };
      });

      window.addEventListener("click", (event) => {
        if (event.target === wishModal) wishModal.style.display = "none";
        if (event.target === displayWishModal)
          displayWishModal.style.display = "none";
      });
    }

    function setupZoomButtons() {
      const zoomInButton = document.getElementById("zoom-in-button");
      const zoomOutButton = document.getElementById("zoom-out-button");
      zoomInButton.onclick = (event) => {
        event.stopPropagation();
        if (!cameraAnimating) controls.dollyIn(1.2);
      };
      zoomOutButton.onclick = (event) => {
        event.stopPropagation();
        if (!cameraAnimating) controls.dollyOut(1.2);
      };
    }

    function onPointerDown(event) {
      pointerStart.set(event.clientX, event.clientY);
    }

    function onPointerUp(event) {
      if (cameraAnimating) return;

      const dist = pointerStart.distanceTo(
        new THREE.Vector2(event.clientX, event.clientY)
      );
      if (dist > 5) return;

      const mouse = new THREE.Vector2();
      mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
      mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

      raycaster.setFromCamera(mouse, camera);

      const intersects = raycaster.intersectObjects(
        lanterns.map((l) => l.group),
        true
      );

      if (intersects.length > 0) {
        let intersectedObject = intersects[0].object;
        while (
          intersectedObject.parent &&
          intersectedObject.parent !== scene
        ) {
          intersectedObject = intersectedObject.parent;
        }

        const foundLantern = lanterns.find(
          (l) => l.group === intersectedObject
        );

        if (foundLantern) {
          document.getElementById("display-wish-message").textContent =
            foundLantern.wish;
          document.getElementById(
            "display-wish-author"
          ).textContent = ` - ${foundLantern.author}`;
          document.getElementById("display-wish-modal").style.display =
            "flex";
        }
      }
    }

    function updateLanternPositions() {
      for (const lantern of lanterns) {
        // LANTERN WILL NEVER DISAPPEAR, IT KEEPS FLYING UP
        lantern.group.position.y += lantern.speed;
        lantern.group.position.x +=
          Math.sin(lantern.time) * lantern.swaySpeed * lantern.swayMagnitude;
        lantern.group.position.z +=
          Math.cos(lantern.time) * lantern.swaySpeed * lantern.swayMagnitude;
        lantern.time += lantern.swaySpeed * 5;

        const positions = lantern.flameParticles.positions;
        const velocities = lantern.flameParticles.velocities;
        const lifetimes = lantern.flameParticles.lifetimes;
        const currentLifetimes = lantern.flameParticles.currentLifetimes;
        const sizes = lantern.flameParticles.sizes;
        const initialSizes = lantern.flameParticles.initialSizes;
        const colors = lantern.flameParticles.colors;

        for (let i = 0; i < positions.count; i++) {
          currentLifetimes[i] += 0.1;
          if (currentLifetimes[i] > lifetimes[i]) {
            positions.setX(i, 0);
            positions.setY(i, 0);
            positions.setZ(i, 0);
            currentLifetimes[i] = 0;
            sizes.setX(i, initialSizes[i]);
          } else {
            positions.setY(i, positions.getY(i) + velocities[i].y);
            positions.setX(i, positions.getX(i) + velocities[i].x);
            positions.setZ(i, positions.getZ(i) + velocities[i].z);

            const lifeRatio = currentLifetimes[i] / lifetimes[i];
            const newOpacity = 1 - lifeRatio;
            colors.setX(i, 1 * newOpacity);
            colors.setY(i, 0.5 * newOpacity);
            colors.setZ(i, 0.1 * newOpacity);
            sizes.setX(i, initialSizes[i] * (1 - lifeRatio));
          }
        }
        positions.needsUpdate = true;
        colors.needsUpdate = true;
        sizes.needsUpdate = true;
      }
    }

    // === MISSING ANIMATE FUNCTION GOES HERE ===
    function animate() {
      requestAnimationFrame(animate);
      if (!cameraAnimating) {
        controls.update();
      }
      TWEEN.update();
      updateLanternPositions();
      renderer.render(scene, camera);
    }
    // ===========================================
    function onWindowResize() {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    }

    init();
  </script>
</body>

</html>