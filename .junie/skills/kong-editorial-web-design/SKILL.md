---
name: kong-editorial-web-design
description: "Design and implement responsive websites in the Kong-inspired editorial connectivity aesthetic: pale gray-green canvases, near-black typography, acid-lime accents, precise one-pixel grids, crosshair intersections, sticky navigation, accumulating section-header stacks, cursor-following glow, technical labels, cinematic imagery, and tactile card interactions. Use when creating or restyling HTML/CSS/JavaScript, React, Vue, Svelte, or other web interfaces that should reproduce this complete visual and interaction system for landing pages, product sites, portfolios, dashboards, documentation, or marketing experiences."
---

# Kong Editorial Web Design

Apply this as a complete system, not as a collection of isolated effects. Preserve the tension between industrial precision and organic imagery: rigid grids, oversized editorial type, muted surfaces, and one electric lime signal color.

Adapt content, information architecture, and imagery to the target website. Do not copy Kong wording, logos, or product names unless the user explicitly requests them.

## Use the bundled typography

Copy or reference these assets:

- `assets/fonts/funnel.woff2` for upright text.
- `assets/fonts/funnel-italic.woff2` for italic text.

Declare the family without fixed weight so the browser can synthesize the full range when only these files are present:

```css
@font-face {
  font-family: Funnel;
  src: url("./assets/fonts/funnel.woff2") format("woff2");
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: Funnel;
  src: url("./assets/fonts/funnel-italic.woff2") format("woff2");
  font-style: italic;
  font-display: swap;
}
```

Use `Funnel, Arial, sans-serif` for display and body text. Use `Roboto Mono, ui-monospace, SFMono-Regular, monospace` only for indexes, system labels, code, counters, and machine-like annotations.

## Establish the visual tokens

Start with these values. Change them only when the target brand requires a deliberate adaptation.

```css
:root {
  --lime: oklch(70.5% .213 47.604);
  --ink: #001408;
  --text: #4a4d49;
  --canvas: #cdd4cb;
  --surface: #d7ded4;
  --surface-high: #e7ede5;
  --grid: #aeb5a7;
  --cross: #4b4e46;
  --dark: #001408;
  --white: #fff;
  --nav-h: 65px;
  --stack-h: 49px;
  --page-gutter: clamp(20px, 4.2vw, 54px);
  --section-pad: clamp(70px, 8vw, 120px);
  --ease-editorial: cubic-bezier(.075, .82, .165, 1);
  --ease-smooth: cubic-bezier(.19, 1, .22, 1);
}
```

Use lime as a signal, not a general fill. Reserve it for primary buttons, highlights behind short words, active borders, links on dark surfaces, cursor light, icons, and selected panels. Keep most of the page muted.

## Build the canvas and grid

- Set the body to `background: var(--canvas)` and `color: var(--text)`.
- Use a centered framed canvas on large screens: `width: calc(100% - 108px); max-width: 1440px; margin-inline: auto`.
- Remove the side margin below approximately 1050px.
- Draw all structural divisions with one-pixel rules. Never use thick borders for layout.
- Use `var(--grid)` for normal rules and `var(--cross)` for junction marks.
- Prefer 2, 3, 4, 5, or 12-column grids separated by 1px gaps over floating cards with large gutters.
- Let the grid container supply the rule color and its children supply the surface color:

```css
.grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 1px;
  background: var(--grid);
  border: 1px solid var(--grid);
}

.grid > * { background: var(--canvas); }
```

- Use rounded corners only for pills, form controls, cookie/privacy panels, and compact rail controls. Keep sections and cards square.
- Avoid generic drop shadows. Use borders, overlays, and lime light. Permit a soft neutral shadow only for floating dialogs or privacy panels.

## Mark every important line intersection

Place an understated 11px crosshair where major horizontal and vertical rules meet. Use a 1px dark line with roughly 70% opacity. Include outer frame corners, navigation boundaries, section corners, logo-cell intersections, card-grid corners, and sticky-header edges.

```css
.junction {
  position: absolute;
  width: 11px;
  height: 11px;
  pointer-events: none;
  z-index: 10;
  opacity: .72;
  background:
    linear-gradient(var(--cross), var(--cross)) center / 1px 11px no-repeat,
    linear-gradient(90deg, var(--cross), var(--cross)) center / 11px 1px no-repeat;
}

.junction.bl { left: -6px; bottom: -6px; }
.junction.br { right: -6px; bottom: -6px; }
.junction.tl { left: -6px; top: -6px; }
.junction.tr { right: -6px; top: -6px; }
```

Use pseudo-elements instead of extra markup when the component does not already use `::before` or `::after`. Do not overwrite card spotlight or border pseudo-elements; put card-grid junctions on the grid container.

## Apply the typography hierarchy

- Use near-black `var(--ink)` for headings and important actions.
- Use gray-green `var(--text)` for paragraphs and inactive labels.
- Render hero headlines at `clamp(48px, 6.75vw, 120px)`, weight 800, line-height between `.92` and `1.05`, and letter-spacing `-.02em` to `-.055em` depending on line length.
- Render large section statements at `clamp(48px, 6.1vw, 88px)`, line-height `.94` to `1`, weight 700–800, and letter-spacing around `-.04em`.
- Render major panel headings at `clamp(40px, 4.25vw, 65px)` with compact line-height.
- Render card headings at 28–34px.
- Keep body copy at 16–19px with line-height 1.35–1.55.
- Render technical labels at 12–14px, weight 700–800, uppercase, letter-spacing `.08em` to `.14em`.
- Use italic Funnel at weight 300 for a human counterpoint beneath a bold hero headline.
- Keep headings short and editorial. Use explicit line breaks when composition matters.
- Highlight only one or two short words with a tight lime rectangle and 2–4px horizontal padding.

## Compose the announcement ticker

Place an optional 36–42px dark ticker above the navigation. Let the ticker scroll away; do not make it sticky.

- Use 12–13px uppercase bold text.
- Separate announcements with `//` at reduced opacity.
- Color key phrases and arrows lime.
- Use an infinite linear marquee around 25–32 seconds.
- Duplicate ticker content once for a seamless loop.
- Stop animation under `prefers-reduced-motion: reduce`.

## Keep the primary navigation sticky

Make the navigation the first sticky layer after the ticker leaves the viewport.

```css
.site-nav {
  position: sticky;
  top: 0;
  z-index: 100;
  height: var(--nav-h);
  display: grid;
  grid-template-columns: 1fr 150px 1fr;
  align-items: center;
  padding-inline: 28px;
  border-bottom: 1px solid var(--grid);
  background: rgb(205 212 203 / 90%);
  backdrop-filter: blur(12px);
}
```

- Center the brand mark on desktop.
- Align primary navigation left and utilities/CTAs right.
- Prefix expandable categories with a lime `+`.
- Style the main CTA as a lime pill and the secondary CTA as a translucent pale pill.
- Collapse to a logo plus hamburger below approximately 1050–1150px.
- Use a 62px mobile navigation height and update `--nav-h` accordingly.
- Do not put a sticky navigation inside any ancestor with `overflow: hidden`, `auto`, `scroll`, `clip`, or a transform.

## Create the hero

- Use a rigid, bordered hero between roughly 500px and 620px tall.
- Center the headline, italic typewriter line, supporting copy, email/CTA form, and secondary text link.
- Add faint vertical rules at approximately 31.25% and 68.75% on desktop.
- Place cinematic cutout imagery at the left and right edges so it enters the grid without covering the text.
- Prefer opposing visual subjects: organic/mechanical, human/machine, old/new, or physical/digital.
- Keep images transparent-background, grayscale, and high-detail when possible.
- Hide or reduce the side imagery below 1000px if it compromises readability.
- Add 11px crosshairs at all four hero corners.

For a typewriter cursor, use a current-color block around `.6ch` wide and `.8em` tall, blinking every `.8s` with `step-end`. Disable it for reduced motion.

Use an email/CTA capsule around 445px × 62px, white background, 100px radius, 8px internal padding, and a lime button. On hover or focus:

- Increase a lime drop-shadow from nearly transparent to visible.
- Slide the opposing hero images inward by approximately 60px using a 1.5–2s editorial easing curve.
- Keep the effect atmospheric; do not make the form look neon.

## Build accumulating sticky section headers

Use a shared ancestor for the entire stack. Place every sticky header as a direct child of that common ancestor with its content immediately after it. Do not wrap each header in a separate section; doing so ends its sticky containing block and prevents accumulation.

```html
<div class="section-stack">
  <div class="stack-header" style="--index: 0">...</div>
  <section>...</section>
  <div class="stack-header" style="--index: 1">...</div>
  <section>...</section>
  <div class="stack-header" style="--index: 2">...</div>
  <section>...</section>
</div>
```

```css
.section-stack { position: relative; }

.stack-header {
  --index: 0;
  position: sticky;
  top: calc(var(--nav-h) + 48px * var(--index) - 1px);
  z-index: 60;
  height: var(--stack-h);
  display: grid;
  grid-template-columns: 58px minmax(0, 1fr) 58px;
  align-items: center;
  background: var(--surface);
  border-top: 1px solid var(--grid);
  border-bottom: 1px solid var(--grid);
}
```

- Increment `--index` from zero.
- Use a 48px increment for 49px headers so neighboring one-pixel borders overlap cleanly.
- Put a monospaced number in the left cell, a compact label in the middle, and a lime symbol in the right cell.
- Scale inactive labels to approximately `.75`; scale the current label to `1` and change it to `var(--ink)`.
- Determine the active panel with an `IntersectionObserver` when active-state animation is required.
- Keep all stack ancestors free of clipping and transforms.
- Let the sticky headers remain below the navigation and above normal content, but below dialogs.

## Design cards as grid cells

Keep cards square, flush, and border-driven. Do not change the whole card to solid lime on hover.

Default state:

- Match the canvas or pale surface.
- Use 32–40px internal padding.
- Use a technical index at the top.
- Leave deliberate vertical space before the title.
- Keep the description short.

Hover/focus state:

- Add a 1px lime outline with `outline-offset: -1px`.
- Add a subtle pale diagonal sheen: `linear-gradient(155deg, rgb(255 255 255 / 24%) -57.71%, transparent 100.25%)`.
- Add a soft lime radial highlight centered on the pointer position.
- Shift the arrow 4–6px to the right.
- Raise the card above adjacent one-pixel borders.
- Apply the same visual response on `:focus-within`.

```css
.card {
  --mx: 50%;
  --my: 50%;
  position: relative;
  isolation: isolate;
  overflow: hidden;
  background: var(--canvas);
  outline: 1px solid transparent;
  outline-offset: -1px;
}

.card::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  opacity: 0;
  background:
    radial-gradient(220px circle at var(--mx) var(--my), oklch(70.5% .213 47.604 / 22%), transparent 72%),
    linear-gradient(155deg, rgb(255 255 255 / 28%) -57.71%, transparent 100.25%);
  transition: opacity .35s;
}

.card:is(:hover, :focus-within) {
  z-index: 2;
  outline-color: var(--lime);
  background:
    linear-gradient(155deg, rgb(255 255 255 / 24%) -57.71%, transparent 100.25%),
    var(--surface);
}

.card:is(:hover, :focus-within)::before { opacity: 1; }
.card:is(:hover, :focus-within) .arrow { transform: translateX(6px); }
```

Update the local spotlight without causing layout:

```js
document.querySelectorAll('.card').forEach((card) => {
  card.addEventListener('pointermove', (event) => {
    const rect = card.getBoundingClientRect();
    card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
    card.style.setProperty('--my', `${event.clientY - rect.top}px`);
  }, { passive: true });
});
```

## Add the cursor-following lime atmosphere

Render one fixed, pointer-events-none glow for the entire page. Keep it subtle enough that text contrast remains unchanged.

```css
.cursor-glow {
  position: fixed;
  left: 0;
  top: 0;
  width: 340px;
  height: 340px;
  margin: -170px 0 0 -170px;
  border-radius: 50%;
  pointer-events: none;
  z-index: 55;
  opacity: 0;
  transform: translate3d(-500px, -500px, 0);
  background: radial-gradient(circle,
    oklch(70.5% .213 47.604 / 13%) 0%,
    oklch(70.5% .213 47.604 / 5.5%) 34%,
    transparent 70%);
  filter: blur(9px);
  mix-blend-mode: multiply;
  transition: opacity .5s;
  will-change: transform;
}

.cursor-glow.visible { opacity: 1; }
```

Move it with one `requestAnimationFrame` update per frame:

```js
const glow = document.querySelector('.cursor-glow');
let x = -500, y = -500, frame = 0;

function paintGlow() {
  glow.style.transform = `translate3d(${x}px, ${y}px, 0)`;
  frame = 0;
}

window.addEventListener('pointermove', (event) => {
  if (event.pointerType === 'touch') return;
  x = event.clientX;
  y = event.clientY;
  glow.classList.add('visible');
  if (!frame) frame = requestAnimationFrame(paintGlow);
}, { passive: true });

document.documentElement.addEventListener('mouseleave', () => {
  glow.classList.remove('visible');
});
```

Keep the glow below sticky headers and navigation. Hide it for coarse pointers, touch screens, and reduced-motion users.

## Use dark and lime comparison panels

For before/after or problem/solution sections, divide the viewport into two flush grid cells:

- Use near-black with pale text for the problem or fragmented state.
- Use solid lime with near-black text for the unified or successful state.
- Use 55–70px desktop padding and at least 410–520px panel height.
- Keep technical headings such as `## WITHOUT` and `## WITH` monospaced.
- Stack the panels vertically on small screens.

Do not overuse solid lime elsewhere; this contrast works because it appears as one major reveal.

## Treat controls and overlays consistently

- Use pill radii around 28–100px for buttons and input capsules.
- Keep lime buttons near-black, bold, and shadowless.
- Use pale secondary buttons with no heavy outline.
- Move arrows slightly on hover rather than scaling the entire button.
- Use a pale translucent privacy panel with 15–18px radius, a thin white edge, and a soft 20–30px neutral shadow.
- Use a small toast as a dark pill with white text.
- Keep dialogs above navigation and sticky stacks.

## Add the optional human/agent rail

On wide screens, place a narrow fixed capsule near the left edge with a lime status dot and vertical letterforms. Use approximately 32px × 222px, 20px radius, translucent pale background, and a one-pixel grid border. Hide it below 1050px. Treat it as atmosphere unless the product genuinely supports a mode toggle.

## Apply motion with restraint

- Use the editorial easing curve for section and image motion.
- Use the smooth curve for hover responses.
- Animate opacity, translate, scale, and filter; avoid animating layout dimensions.
- Use 250–400ms for hover transitions, 600–800ms for panel entrances, and up to 2s for large hero imagery.
- Use `requestAnimationFrame` for pointer-following effects.
- Never add parallax, bounce, random particles, or generic glassmorphism.

## Make the system responsive

At approximately 1050–1150px:

- Remove the outer 54px frame gutters.
- Hide the side rail.
- Collapse desktop navigation.
- Reduce or reposition hero imagery.

At approximately 700–768px:

- Set `--nav-h: 62px`.
- Reduce hero headlines to roughly 60–68px.
- Remove decorative hero vertical rules if they interfere with text.
- Make forms nearly full width with 18–20px page margins.
- Stack comparison panels and card grids into one column.
- Reduce sticky-header side cells from 58px to about 44px.
- Keep stacked headers active; do not remove the defining scroll behavior.
- Hide the cursor glow and pointer-local card glow on touch devices.
- Show no more than three logos per row unless the logo strip becomes horizontally scrollable.

## Preserve accessibility and performance

- Use semantic `header`, `nav`, `main`, `section`, `article`, and `footer` elements.
- Maintain visible keyboard focus. Mirror hover effects with `:focus-visible` or `:focus-within`.
- Keep body text contrast at or above WCAG AA.
- Provide useful alt text for meaningful images and empty alt text for decoration.
- Never rely on lime alone to communicate state; pair it with text, position, or shape.
- Disable marquee, typewriter blink, image travel, and cursor glow under `prefers-reduced-motion: reduce`.
- Use `pointer-events: none` for all decorative overlays.
- Update pointer effects with CSS variables and transforms only.
- Avoid scroll listeners for sticky behavior; let CSS `position: sticky` perform the work.
- Test sticky ancestors for accidental overflow or transforms before adding JavaScript workarounds.

## Follow this implementation workflow

1. Inspect the existing website structure and preserve required functionality.
2. Define the complete token set before styling components.
3. Load Funnel and establish the typography hierarchy.
4. Build the framed canvas, one-pixel grid, and crosshair junctions.
5. Add the ticker and sticky navigation.
6. Compose the hero with editorial type and opposing imagery.
7. Build the shared sticky section stack with correct direct-child structure.
8. Add comparison panels, logo grids, cards, and dark footer.
9. Add card-local light, global cursor glow, arrow motion, and form/hero response.
10. Implement mobile breakpoints and reduced-motion behavior.
11. Verify desktop and mobile visually at minimum 1280×720 and 390×844.
12. Scroll through the full page and confirm the navigation stays at `top: 0`, section headers accumulate at 48px offsets, content remains readable beneath the stack, and the final stack releases at its containing block boundary.
13. Hover and keyboard-focus every interactive component. Confirm lime outlines do not shift layout.
14. Check the console for errors and validate that no decorative layer intercepts clicks.

## Reject common mismatches

Do not ship the result if it uses:

- generic white cards floating on a gray background;
- thick borders or excessive rounded corners;
- a full solid-lime card hover;
- a navigation bar that scrolls away;
- section headers that replace one another instead of accumulating;
- crosshairs placed randomly rather than at line intersections;
- a bright cursor spotlight that reduces readability;
- several competing accent colors;
- oversized shadows, gradients, or glass effects;
- default system typography when the Funnel assets are available;
- touch-only effects that require hover;
- JavaScript scroll positioning when CSS sticky positioning is sufficient.

The finished website should feel measured, technical, tactile, and slightly uncanny: a restrained infrastructure grid energized by one acidic color and carefully controlled motion.
