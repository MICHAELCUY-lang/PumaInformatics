<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable - PUMA IT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background-color: #0A1128; color: #ffffff; display: flex; align-items: center; justify-content: center; min-height: 100vh; overflow: hidden; }
        .container { text-align: center; max-w-2xl; padding: 2rem; position: relative; z-index: 10; }
        .label { display: inline-block; color: #D4AF37; font-size: 0.875rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 1.5rem; }
        h1 { font-family: 'Playfair Display', serif; font-size: 4rem; margin-top: 0; margin-bottom: 2rem; font-weight: 400; }
        p { color: rgba(255,255,255,0.7); font-size: 1.125rem; font-weight: 300; line-height: 1.6; margin-bottom: 3rem; }
        a { display: inline-block; padding: 1rem 2rem; background-color: #ffffff; color: #0A1128; text-decoration: none; font-size: 0.875rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; transition: all 0.3s ease; }
        a:hover { background-color: #D4AF37; color: #ffffff; }
        .bg-element { position: absolute; border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; pointer-events: none; }
    </style>
</head>
<body>
    <div class="bg-element" style="width: 800px; height: 800px; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
    <div class="container">
        <span class="label">Maintenance Mode</span>
        <h1>Service Unavailable</h1>
        <p>We are currently performing scheduled maintenance to improve our systems. We will be back shortly.</p>
        <button onclick="window.location.reload()" style="display: inline-block; padding: 1rem 2rem; background-color: #ffffff; color: #0A1128; text-decoration: none; font-size: 0.875rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#D4AF37'; this.style.color='#ffffff';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#0A1128';">Try Again</button>
    </div>
</body>
</html>
