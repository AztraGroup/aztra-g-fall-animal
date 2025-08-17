# Aztra G

WordPress plugin providing an interface to build and chat with models via an n8n workflow.

## Instalação

1. Dentro deste diretório gere o pacote distribuível:

   ```bash
   zip -r aztra-g.zip .
   ```

   ou baixe o arquivo `aztra-g.zip` pronto.
2. No painel do WordPress vá em **Plugins → Adicionar novo → Enviar plugin**, selecione o `.zip` e ative.
3. A ativação cria automaticamente as páginas listadas em [Páginas geradas](#p%C3%A1ginas-geradas).

## Páginas geradas

- **Aztra — Home**
- **Chat**
- **Builder**
- **Gere Seu Agente**
- **Gallery**
- **Política de Privacidade**
- **Termos de Uso**
- **Commands**
- **Tutoriais**

O alternador de tema funciona em todas as páginas e a última resposta gerada é mantida em `localStorage` para ser exibida novamente na home.

## Shortcodes

- `[aztra_home]` – landing page with a sample JSON preview and button to save your webhook before starting a conversation.
- `[aztra_builder]` – form to configure model parameters and send them to the workflow.
- `[aztra_chat]` – two-column chat interface supporting file uploads.
- `[aztra_gallery]` – list of saved responses for the current user.
- `[aztra_agent]` – simple generator that guides users to create an agent webhook.
- `[aztra_privacy]` and `[aztra_terms]` – render the Privacy Policy and Terms of Use from the settings.
- `[aztra_commands]` – placeholder page for global commands and customisation.
- `[aztra_tutorials]` – tabs with introduction, examples and FAQ.

## Rotas REST

- `POST /wp-json/aztra/v1/generate`
- `POST /wp-json/aztra/v1/signup`
- `GET /wp-json/aztra/v1/lists`
- `POST /wp-json/aztra/v1/webhook`
- `POST /wp-json/aztra/v1/chat/send`
- `GET /wp-json/aztra/v1/chat/list`

## Tradução

Os textos do plugin utilizam funções de internacionalização do WordPress. Crie arquivos `.po` e `.mo` em `languages/` para adicionar novos idiomas. Ferramentas como [Loco Translate](https://wordpress.org/plugins/loco-translate/) podem ajudar no processo.

## Acessibilidade

As páginas e widgets seguem boas práticas de acessibilidade do WordPress. Ao personalizar, garanta contraste adequado entre cores, forneça textos alternativos para imagens e teste a navegação por teclado.

## Configurações

Em **Aztra G → Settings** você pode definir o webhook, listas de opções para o builder e os templates de política de privacidade e termos. Os templates aceitam os placeholders `{company_name}` e `{contact_email}`, substituídos automaticamente no front‑end.

## Desenvolvimento

O código-fonte está neste diretório; os assets ficam em `assets/`. Verificações de sintaxe podem ser executadas com:

```bash
php -l aztra-g.php
php -l includes/class-aztra-shortcodes.php
```

### Build

Para gerar um pacote distribuível execute:

```bash
zip -r aztra-g.zip .
```

---
This repository is intended for experimentation with the Aztra G console and is not an official release.
