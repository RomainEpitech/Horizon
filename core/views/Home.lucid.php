<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizon PHP Framework</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            position: relative;
            overflow: hidden;
            perspective: 1000px;
        }

        .space-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, 
                #000000 0%,
                #0a0a2a 40%,
                #1a1a3a 60%,
                #2a2a4a 80%,
                #3a3a5a 100%
            );
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 1; }
        }

        @keyframes shooting {
            0% {
                transform: translateX(0) translateY(0) rotate(-45deg);
                opacity: 1;
            }
            100% {
                transform: translateX(-500px) translateY(500px) rotate(-45deg);
                opacity: 0;
            }
        }

        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            animation: starRotate 240s linear infinite;
        }

        @keyframes starRotate {
            from { transform: rotateY(0deg); }
            to { transform: rotateY(360deg); }
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #fff;
            border-radius: 50%;
        }

        .shooting-star {
            position: absolute;
            width: 100px;
            height: 1px;
            background: linear-gradient(to right, rgba(255,255,255,0), rgba(255,255,255,1));
            animation: shooting 3s linear infinite;
            opacity: 0;
        }

        .horizon-line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, 
                rgba(65, 88, 208, 0) 0%,
                rgba(65, 88, 208, 0.3) 20%,
                rgba(65, 88, 208, 0.8) 50%,
                rgba(65, 88, 208, 0.3) 80%,
                rgba(65, 88, 208, 0) 100%
            );
            box-shadow: 0 0 50px 10px rgba(65, 88, 208, 0.5);
        }

        .grid {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50vh;
            background-image: 
                linear-gradient(to bottom, 
                    rgba(65, 88, 208, 0.1) 1px, 
                    transparent 1px),
                linear-gradient(to right, 
                    rgba(65, 88, 208, 0.1) 1px, 
                    transparent 1px);
            background-size: 50px 50px;
            transform: perspective(500px) rotateX(60deg);
            transform-origin: bottom;
        }

        .container {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 1000px;
            margin: 20px;
        }

        h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #4158D0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 30px rgba(65, 88, 208, 0.5);
            position: relative;
        }

        .version {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 3rem;
        }

        .links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 0 auto;
        }

        .link-card {
            background: rgba(65, 88, 208, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 1px solid rgba(65, 88, 208, 0.2);
        }

        .link-card:hover {
            transform: translateY(-5px);
            background: rgba(65, 88, 208, 0.2);
            border-color: rgba(65, 88, 208, 0.3);
            box-shadow: 0 8px 32px rgba(65, 88, 208, 0.2);
        }

        .link-card h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .link-card p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 3rem;
            }
            
            .links {
                grid-template-columns: 1fr;
            }
        }
        .planet {
            position: absolute;
            border-radius: 50%;
            transform-style: preserve-3d;
        }

        .planet::before {
            content: '';
            position: absolute;
            width: 140%;
            height: 20px;
            background: rgba(255,255,255,0.1);
            left: -20%;
            top: 50%;
            transform: translateY(-50%) rotateX(75deg);
            border-radius: 50%;
            filter: blur(2px);
        }
        .planet-1 {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #4a5a8c, #7986b5);
            top: 20%;
            left: 15%;
            animation: 
                planet1Float 15s ease-in-out infinite,
                planetRotate 20s linear infinite;
        }
        .planet-2 {
            width: 150px;
            height: 150px;
            background: linear-gradient(45deg, #8c4a6e, #b57986);
            bottom: 15%;
            right: 10%;
            animation: 
                planet2Float 20s ease-in-out infinite,
                planetRotate 25s linear infinite reverse;
        }
        .planet-3 {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #4a8c7d, #79b5a8);
            top: 30%;
            right: 20%;
            animation: 
                planet3Float 12s ease-in-out infinite,
                planetRotate 15s linear infinite;
        }
        @keyframes planet1Float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(30px, 20px) rotate(90deg);
            }
            50% {
                transform: translate(0px, 40px) rotate(180deg);
            }
            75% {
                transform: translate(-30px, 20px) rotate(270deg);
            }
            100% {
                transform: translate(0, 0) rotate(360deg);
            }
        }
        @keyframes planet2Float {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(-40px, -20px) rotate(120deg) scale(1.1);
            }
            66% {
                transform: translate(40px, -40px) rotate(240deg) scale(0.9);
            }
            100% {
                transform: translate(0, 0) rotate(360deg) scale(1);
            }
        }
        @keyframes planet3Float {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            50% {
                transform: translate(20px, 30px) rotate(180deg) scale(1.2);
            }
            100% {
                transform: translate(0, 0) rotate(360deg) scale(1);
            }
        }
        @keyframes planetRotate {
            from {
                filter: brightness(1) hue-rotate(0deg);
            }
            to {
                filter: brightness(1.2) hue-rotate(360deg);
            }
        }
        .rings {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            pointer-events: none;
            animation: ringsRotate 30s linear infinite;
        }
        .rings-1 {
            width: 140px;
            height: 30px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transform: translate(-50%, -50%) rotateX(75deg);
        }
        .rings-2 {
            width: 200px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.15);
            transform: translate(-50%, -50%) rotateX(75deg);
        }
        .rings-3 {
            width: 110px;
            height: 25px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transform: translate(-50%, -50%) rotateX(75deg);
        }
        @keyframes ringsRotate {
            from {
                transform: translate(-50%, -50%) rotateX(75deg) rotate(0deg);
            }
            to {
                transform: translate(-50%, -50%) rotateX(75deg) rotate(360deg);
            }
        }
        .crater {
            position: absolute;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            animation: craterFade 8s ease-in-out infinite alternate;
        }

        @keyframes craterFade {
            from { opacity: 0.2; }
            to { opacity: 0.4; }
        }
    </style>
</head>
<body>
    <div class="space-background"></div>
    <div class="stars" id="stars"></div>
    <div class="planet planet-1">
        <div class="rings rings-1"></div>
    </div>
    
    <div class="planet planet-2">
        <div class="rings rings-2"></div>
    </div>
    
    <div class="planet planet-3">
        <div class="rings rings-3"></div>
    </div>
    
    <div class="horizon-line"></div>
    <div class="grid"></div>
    <div class="container">
        <h1>Horizon</h1>
        <div class="version">v1.0.0</div>
        
        <div class="links">
            <a href="/docs" class="link-card">
                <h3>Documentation</h3>
                <p>Guides complets et références détaillées pour démarrer</p>
            </a>
            
            <a href="/guide" class="link-card">
                <h3>Guide de démarrage</h3>
                <p>Créez votre première application en quelques minutes</p>
            </a>
            
            <a href="https://github.com/horizon/horizon" class="link-card">
                <h3>GitHub</h3>
                <p>Contribuez au développement d'Horizon</p>
            </a>
            
            <a href="/ecosystem" class="link-card">
                <h3>Écosystème</h3>
                <p>Découvrez les packages et outils officiels</p>
            </a>
        </div>
    </div>

    <script>
        const starsContainer = document.getElementById('stars');
        for (let i = 0; i < 200; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.left = `${Math.random() * 100}%`;
            star.style.top = `${Math.random() * 100}%`;
            star.style.animation = `twinkle ${Math.random() * 3 + 2}s infinite ${Math.random() * 2}s`;
            star.style.transform = `translateZ(${Math.random() * 500}px)`;
            starsContainer.appendChild(star);
        }
        setInterval(() => {
            const shootingStar = document.createElement('div');
            shootingStar.className = 'shooting-star';
            shootingStar.style.top = `${Math.random() * 50}%`;
            shootingStar.style.right = '0';
            document.body.appendChild(shootingStar);
            
            setTimeout(() => {
                shootingStar.remove();
            }, 3000);
        }, 8000);
        function addCraters(planet, numCraters) {
            for (let i = 0; i < numCraters; i++) {
                const crater = document.createElement('div');
                crater.className = 'crater';
                const size = Math.random() * 10 + 5;
                crater.style.width = size + 'px';
                crater.style.height = size + 'px';
                crater.style.left = Math.random() * 70 + 15 + '%';
                crater.style.top = Math.random() * 70 + 15 + '%';
                planet.appendChild(crater);
            }
        }

        document.querySelectorAll('.planet').forEach(planet => {
            addCraters(planet, 5);
        });
    </script>
</body>
</html>