## About

This repo contains the source code of https://bajki-muza.pl,
a knowledge base about audio tales recorded by Polskie Nagrania Muza.

<div align="center">
	<img
		src="resources/screenshots/index-light.png#gh-light-mode-only"
		alt="Screenshot of tales index"
		width="48%"
	>
	<img
		src="resources/screenshots/tale-light.png#gh-light-mode-only"
		alt="Screenshot of tale view"
		width="48%"
	>
	<img
		src="resources/screenshots/index-dark.png#gh-dark-mode-only"
		alt="Screenshot of tales index"
		width="48%"
	>
	<img
		src="resources/screenshots/tale-dark.png#gh-dark-mode-only"
		alt="Screenshot of tale view"
		width="48%"
	>
</div>

## Installation

Clone this repository and install it like you normally install Laravel application.

- Install dependencies (`composer install && pnpm install`)
- Create `.env` using `composer setup` script
- Run database migrations (`php artisan migrate`)
- Start dev server with `composer dev`

## Testing

Test your changes using `composer test`, `composer check` and `composer format` scripts.
