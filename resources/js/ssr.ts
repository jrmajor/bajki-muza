import { createInertiaApp } from '@inertiajs/svelte';
import createServer from '@inertiajs/svelte/server';
import { resolve } from './common';

createServer((page) => createInertiaApp({ page, resolve }));
