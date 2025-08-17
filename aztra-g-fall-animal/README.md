# Aztra G

WordPress plugin providing an interface to build and chat with models via an n8n workflow. The plugin registers several shortcodes:

- `[aztra_home]` – landing page with a sample JSON preview and button to save your webhook before starting a conversation.
- `[aztra_builder]` – form to configure model parameters and send them to the workflow.
- `[aztra_chat]` – two-column chat interface supporting file uploads.
- `[aztra_gallery]` – list of saved responses for the current user.
- `[aztra_agent]` – simple generator that guides users to create an agent webhook.
- `[aztra_privacy]` and `[aztra_terms]` – render the Privacy Policy and Terms of Use from the settings.
- `[aztra_commands]` – placeholder page for global commands and customisation.
- `[aztra_tutorials]` – tabs with introduction, examples and FAQ.

## Setup

1. Upload the plugin to your WordPress installation and activate it. Activation creates the pages **Aztra — Home**, **Chat**, **Builder**, **Gere Seu Agente**, **Gallery**, **Política de Privacidade**, **Termos de Uso**, **Commands** and **Tutoriais**.
2. On first visit, go to **Aztra — Home** to preview the webhook response.
3. Use **Salvar Modelo e iniciar conversa** to store your production webhook and open the chat.

The theme toggle works across all pages and your last generated response is kept in `localStorage` to be previewed on the home screen.

## Settings

Under **Aztra G → Settings** you can define the webhook, lists of options for the builder and the templates for privacy policy and terms. The templates support the placeholders `{company_name}` and `{contact_email}` which are replaced automatically on the front end.

## Development

Source code lives in the `aztra-g-fall-animal` directory. Assets are in `assets/`. PHP lint can be run with:

```bash
php -l aztra-g.php
php -l includes/class-aztra-shortcodes.php
```

---
This repository is intended for experimentation with the Aztra G console and is not an official release.
