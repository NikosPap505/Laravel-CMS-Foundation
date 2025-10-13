<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Required — Run Migrations</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        :root { --bg: #0f172a; --card: #111827; --text: #e5e7eb; --muted: #9ca3af; --accent: #22d3ee; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; background: radial-gradient(1200px 800px at 50% -10%, #0ea5e9 0%, transparent 50%), var(--bg); color: var(--text);}
        .container { max-width: 880px; margin: 0 auto; padding: 48px 20px; }
        .card { background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02)); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.35);}
        h1 { font-size: 28px; margin: 0 0 8px; letter-spacing: -0.02em; }
        p { margin: 0 0 14px; color: var(--muted); line-height: 1.6; }
        code, pre { background: #0b1220; color: #e5e7eb; border-radius: 8px; padding: 2px 6px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        pre { padding: 14px; overflow: auto; }
        .steps { margin-top: 20px; display: grid; gap: 16px; }
        .step { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 16px; }
        .muted { color: var(--muted); }
        .footer { margin-top: 22px; font-size: 12px; color: var(--muted); }
        a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Setup required: create database tables</h1>
        <p>Your application is running, but the <code>pages</code> table has not been created yet.</p>
        <div class="steps">
            <div class="step">
                <strong>1) Copy .env and generate the app key</strong>
                <pre><code>cp .env.example .env
php artisan key:generate</code></pre>
            </div>
            <div class="step">
                <strong>2) Use SQLite (default) or configure another database</strong>
                <p class="muted">SQLite quick start:</p>
                <pre><code>mkdir -p database
# If you prefer a custom name, set DB_DATABASE in .env
# otherwise, default database/database.sqlite is used
php artisan migrate</code></pre>
            </div>
            <div class="step">
                <strong>3) Seed demo content (optional)</strong>
                <pre><code>php artisan db:seed</code></pre>
            </div>
        </div>
        <p class="footer">After running migrations, refresh this page. You should see your Home page content. If you continue to see this screen, ensure that your DB connection is correct and that the <code>pages</code> table exists.</p>
    </div>
</div>
</body>
</html>
