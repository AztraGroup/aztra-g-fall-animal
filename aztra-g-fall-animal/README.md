# Aztra G Fall Animal

WordPress plugin providing an interface to build and chat with Animal Flight models. The plugin registers several shortcodes:

- `[aztra_home]` – landing page with a sample JSON preview and button to save your webhook before starting a conversation.
- `[aztra_builder]` – form to configure model parameters and send them to the workflow.
- `[aztra_chat]` – two-column chat interface supporting file uploads.
- `[aztra_gallery]` – list of saved responses for the current user.
- `[aztra_login]` and `[aztra_signup]` – basic authentication pages.

## Setup

1. Upload the plugin to your WordPress installation and activate it.
2. On first visit, go to **Aztra — Home** to preview the webhook response.
3. Use **Salvar Modelo e iniciar conversa** to store your production webhook and open the chat.

The theme toggle works across all pages and your last generated response is kept in `localStorage` to be previewed on the home screen.

## Development

Source code lives in the `aztra-g-fall-animal` directory. Assets are in `assets/`. PHP lint can be run with:

```bash
php -l aztra-g-fall-animal.php
php -l includes/class-aztra-shortcodes.php
```

---
This repository is intended for experimentation with the Aztra G console and is not an official release.
