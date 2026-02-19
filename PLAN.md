# Betel Info

Betel Info is a public church info-hub that lists events and useful information for the church community.

## Public Website

### Layout

From top to bottom, the landing page contains:

- church logo
- title
- short description
- content: info items, either grouped or standalone

### Features

Info items can be of 2 types:

1. Rich text (markdown)
2. Cards
3. Posters

The content is a list of Rich Text items, Cards, Titled Groups. Groups can include cards or posters

Cards have the following content structure:
- title: mandatory
- description: mandatory
- thumbnail: optional (16:9 aspect ratio)
- date: optional
- time: optional (only when date is provided)
- link: optional

Posters have the following content structure:
- image: mandatory (9:16 aspect ratio expected, vertical)
- title: mandatory
- link: optional

Groups have the following content structure:
- title: mandatory
- items: a list of cards and posters

### Technical Details

This is a single page application that can also be installed as a PWA.
The application is to be built using React and Vite.
Tailwind css is to be used for styling, with clear theming set up for future customization (based on user prefferences, or multi-site theme configuration)
The application is just a shell. It is important that the bundled files contain a hash such that updates will break cache policies in browsers.
The app shell will include a splash screen / loading screen while the content is being fetched.
The content is fetched from 2 JSON files:
1. Page content (logo, title, description, etc.) - this doesn't change very often
2. Info content (the list of cards, groups, rich text, and posters) - this is expected to change every couple days
