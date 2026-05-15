import type { createHead as createServerHead } from '@unhead/vue/server';
import { transformHtmlTemplate } from '@unhead/vue/server';
import express from 'express';
import fs from 'node:fs/promises';
import { createServer as createHttpServer } from 'node:http';
import path from 'node:path';
import { pathToFileURL } from 'node:url';
import { createServer as createViteServer } from 'vite';
import { uneval } from 'devalue';
import type { InitialState } from './src/types/initial-state';

type ManifestChunk = {
  file: string;
  css?: string[];
  imports?: string[];
};

type Manifest = Record<string, ManifestChunk>;

type ServerHead = ReturnType<typeof createServerHead>;

type RenderResult = {
  html: string;
  status: number;
  head: ServerHead;
  state: InitialState;
};

type ServerEntry = {
  render: (url: string) => Promise<RenderResult>;
};

const port = Number(process.env.PORT) || 5173;
const root = process.cwd();
const isProduction = process.env.NODE_ENV === 'production';

const templatePath = path.resolve(root, 'index.html');

const serverEntryUrl = pathToFileURL(path.resolve(root, 'dist/server/entry-server.ts')).href;

const renderClientAssets = (
  manifest: Manifest | null,
): { headTags: string; clientEntry: string } => {
  if (!manifest) {
    return {
      headTags: '<link rel="stylesheet" href="/src/assets/main.css" />',
      clientEntry: '<script type="module" src="/src/entry-client.ts"></script>',
    };
  }

  const chunk = manifest['src/entry-client.ts'];

  return {
    headTags: (chunk?.css ?? [])
      .map((cssFile) => `<link rel="stylesheet" href="/${cssFile}">`)
      .join('\n'),
    clientEntry: chunk ? `<script type="module" src="/${chunk.file}"></script>` : '',
  };
};

const renderInitialState = (state: InitialState): string => {
  return `<script>window.__INITIAL_STATE__ = ${uneval(state)}</script>`;
};

async function createServer() {
  const app = express();
  const httpServer = createHttpServer(app);

  const vite = isProduction
    ? null
    : await createViteServer({
        root,
        server: {
          middlewareMode: true,
          hmr: {
            server: httpServer,
          },
        },
        appType: 'custom',
      });

  if (vite) {
    app.use(vite.middlewares);
  } else {
    app.use(express.static(path.resolve(root, 'dist/client'), { index: false }));
  }

  const manifest: Manifest | null = isProduction
    ? JSON.parse(await fs.readFile(path.resolve(root, 'dist/client/.vite/manifest.json'), 'utf-8'))
    : null;

  app.use(async (req, res, next) => {
    try {
      const url = req.originalUrl;

      let template = await fs.readFile(templatePath, 'utf-8');

      const serverEntry = vite
        ? ((await vite.ssrLoadModule('/src/entry-server.ts')) as ServerEntry)
        : ((await import(/* @vite-ignore */ serverEntryUrl)) as ServerEntry);

      if (vite) {
        template = await vite.transformIndexHtml(url, template);
      }

      const rendered = await serverEntry.render(url);
      const { headTags, clientEntry } = renderClientAssets(manifest);

      const html = template
        .replace('<!--head-tags-->', headTags)
        .replace('<!--app-html-->', rendered.html)
        .replace('<!--initial-state-->', renderInitialState(rendered.state))
        .replace('<!--client-entry-->', clientEntry);

      const htmlWithHead = transformHtmlTemplate(rendered.head, html);

      res.status(rendered.status).set({ 'Content-Type': 'text/html' }).end(htmlWithHead);
    } catch (e) {
      vite?.ssrFixStacktrace(e as Error);
      next(e);
    }
  });

  httpServer.listen(port, '0.0.0.0', () => {
    console.log(`SSR server running at http://localhost:${port}`);
  });
}

createServer().then();
