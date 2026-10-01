import express from 'express';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';
import { apiRouter } from './server/api.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function startServer() {
  const app = express();
  const PORT = Number(process.env.PORT) || 3000;
  const isProd = process.env.NODE_ENV === 'production' || !process.env.DISABLE_HMR;

  // JSON Body Parser for backend API
  app.use(express.json());
  app.use(express.urlencoded({ extended: true }));

  // Enable CORS for WordPress & BarberLoo.in domain embedding
  app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-WPPusher-Client, X-Requested-With');
    if (req.method === 'OPTIONS') {
      return res.sendStatus(200);
    }
    next();
  });

  // Mount Backend API
  app.use('/api', apiRouter);

  // Serve wppusher.js directly
  app.get('/wppusher.js', (req, res) => {
    res.setHeader('Content-Type', 'application/javascript; charset=UTF-8');
    res.sendFile(path.resolve(__dirname, 'wppusher.js'));
  });

  const distPath = path.resolve(__dirname, 'dist');
  const hasDist = fs.existsSync(distPath);

  if (hasDist && process.env.NODE_ENV === 'production') {
    // Production Mode: Serve built files from dist
    app.use(express.static(distPath));

    app.use((req, res, next) => {
      let reqPath = req.path;
      if (reqPath.endsWith('/')) {
        reqPath += 'index.html';
      }

      // 1. Direct file check in dist
      let candidate = path.join(distPath, reqPath);
      if (fs.existsSync(candidate) && fs.statSync(candidate).isFile()) {
        return res.sendFile(candidate);
      }

      // 2. Try with .html extension
      if (!reqPath.includes('.')) {
        let htmlCandidate = path.join(distPath, `${reqPath}.html`);
        if (fs.existsSync(htmlCandidate) && fs.statSync(htmlCandidate).isFile()) {
          return res.sendFile(htmlCandidate);
        }
      }

      // 3. Fallback to index.html
      res.sendFile(path.join(distPath, 'index.html'));
    });
  } else {
    // Dev Mode: Mount Vite as middleware so all live HTML and TS files resolve instantly
    const { createServer: createViteServer } = await import('vite');
    const vite = await createViteServer({
      server: {
        middlewareMode: true,
        hmr: process.env.DISABLE_HMR !== 'true',
      },
      appType: 'mpa',
    });

    app.use(vite.middlewares);

    // Route rewrite fallback for clean URLs in dev
    app.use((req, res, next) => {
      let reqPath = req.path;
      if (!reqPath.includes('.') && reqPath !== '/') {
        let filePath = path.resolve(__dirname, `.${reqPath}.html`);
        if (fs.existsSync(filePath)) {
          return res.redirect(`${reqPath}.html`);
        }
      }
      next();
    });
  }

  app.listen(PORT, '0.0.0.0', () => {
    console.log(`BarberLoo fullstack server listening on 0.0.0.0:${PORT}`);
  });
}

startServer();
