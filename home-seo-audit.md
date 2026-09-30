# Home Page – Full SEO Audit (ipagame.store)

Audited: the rendered home page on the local site (`http://localhost/ipa game site/`) on 2026-09-30, plus `robots.txt`, `sitemap.xml`, `.htaccess` and response headers. Values that depend on the live domain (canonical, sitemap URLs, `og:url`) show `localhost` here and must be re-checked once on `https://ipagame.store`.

**Overall: 8 / 10.** The technical base is strong. The gaps are mostly small metadata, dead social links, image dimensions, and off-page signals.

---

## 1. What is already good (keep)

| Area | Finding |
|------|---------|
| Title | `IPA Game Store – Free IPA Games for iPhone & iPad` (53 chars, keyword up front, under 60) |
| Meta description | 150 chars, has games count, "version history", "file size", "install guides" |
| Canonical | Present, self-referencing |
| Headings | One `H1` ("Free IPA Games for iPhone & iPad"), 10 keyword-rich `H2`s, `H3`s nested correctly |
| Content depth | ~1,870 visible words (was ~120 in the first audit) |
| Structured data | `WebSite` + `SearchAction`, `Organization` + `ContactPoint`, `FAQPage` (12 Q&A, matches the visible FAQ) |
| Internal links | 165 links, only 1 external. Strong links to categories, guides, and the device pages |
| Speed basics | Hero image preloaded with `fetchpriority="high"`, JS is `defer`, 78 of 83 images `loading="lazy"`, CSS/JS/images cached for 30 days, `mod_deflate` on, preconnect to fonts and image CDN |
| Crawl | `robots.txt` blocks `/search/`, `/out/`, `/dl/`, `/downloads/`, `/api/` and lists the sitemap. Sitemap returns 200 with `lastmod`. `index.php` 301-redirects, unknown URLs return a real 404, `www` redirects to the bare domain |
| Mobile | `viewport` meta, `theme-color`, no horizontal scroll at 375 px |
| Security headers | `nosniff`, `Referrer-Policy`, `X-Frame-Options` |
| Trust pages | `/about/`, `/contact/`, `/privacy/`, `/dmca/` all return 200 |

---

## 2. Issues, in priority order

### High

| # | Issue | Why it matters | Fix |
|---|-------|----------------|-----|
| H1 | **Social links are dead.** Telegram, X and YouTube in the hero use `href="#"` with `target="_blank"`. `Organization.sameAs` is an empty array. | Dead links hurt UX. Empty `sameAs` loses the brand-entity signal that helps Google connect the site to its profiles. | Put the real profile URLs in both the buttons and `sameAs` in `routes/home.php` (or config). If a profile does not exist yet, remove the button. |
| H2 | **37 of 83 `<img>` tags have no `width`/`height`.** | Causes layout shift (CLS), which is a Core Web Vitals signal. | Add width/height (or `aspect-ratio` in CSS) to the filmstrip and shelf images. The icons already have sizes, so start with the screenshots in `.frame-img` and the poster/device images. |
| H3 | **Google Fonts is a render-blocking stylesheet** (Bebas Neue, Unbounded, Inter). | Delays first paint, hurts LCP on slow mobile. | Keep `display=swap` (already set), trim unused weights, or self-host the fonts as `woff2` with `preload`. |
| H4 | **No off-page signals visible from the code** (Search Console, Bing Webmaster, backlinks). | A new site does not rank on on-page work alone. | Verify the domain in Google Search Console and Bing, submit `sitemap.xml`, and build links (see section 4). |

### Medium

| # | Issue | Why it matters | Fix |
|---|-------|----------------|-----|
| M1 | **Open Graph is incomplete.** Missing `og:image:width`, `og:image:height`, `og:image:alt`, `og:locale`. | Some networks show a poor or cropped preview. | Add 1200×630 dimensions, alt text and `og:locale=en_US`. |
| M2 | **Twitter card tags are incomplete.** Only `twitter:card` is present. | Most platforms fall back to `og:*`, but not all. | Add `twitter:title`, `twitter:description`, `twitter:image`. |
| M3 | **FAQPage rich results are limited.** Since 2023 Google shows FAQ rich results only for well-known government and health sites. | The schema will not give the expandable snippet. | Keep it (it is valid and helps understanding, and the visible FAQ helps users), but do not expect a SERP feature. |
| M4 | **No list schema for the game sections.** | An `ItemList` can help Google understand the "Latest" and "Top 10" lists. | Add `ItemList` JSON-LD for the Top 10 chart (name, position, URL for each game). |
| M5 | **Ten "Read more" style links use short anchors like "See all →".** | Weak anchor text wastes internal-link signal. | Use descriptive text, for example "See all latest IPA games", "See all offline IPA games". Can be done with visible text or `aria-label`. |
| M6 | **`manifest.webmanifest` returns a 301.** | Manifest requests should return 200 directly. | Serve the manifest from a real file or fix the redirect, only if a manifest is linked. |
| M7 | **16 images have an empty `alt`.** Most are the small game icons in the filmstrip and Top 10 and the logo inside the home link. | Empty `alt` is correct for decorative icons whose name is next to them, so this is only a problem where the image is the only content of a link. | Leave the decorative ones. Check any icon that sits alone inside a link and give it the game name. |

### Low

| # | Issue | Fix |
|---|-------|-----|
| L1 | Meta description says `1197+` (no thousands separator), the page body says `1,197+`. | Use `number_format` in both places for consistency. |
| L2 | No `hreflang`. | Not needed for a single-language site. Skip unless you add languages. |
| L3 | No `Content-Security-Policy` or `Permissions-Policy` headers. | Optional hardening, no direct ranking effect. |
| L4 | Three quick-nav guide links exist only in the header and inside the "3 Steps" card, since the Academy section was removed. | Consider a small "Guides" link row near the FAQ, so the 13 guides get more internal links from the home page. |
| L5 | `/terms/` returns 404. | Only matters if the footer or any page links to it. Add a terms page or remove the link. |

---

## 3. Keyword coverage on the page

| Target keyword | In title | In H1 | In H2s | In body |
|----------------|:-------:|:-----:|:------:|:-------:|
| ipa games | yes | yes | yes | yes |
| free ipa games | yes | yes | yes | yes |
| ipa games for iphone / ipad | yes | yes | – | yes |
| ios games ipa | – | – | yes | yes |
| latest / new ipa games | – | – | yes | yes |
| offline ipa games | – | – | yes | yes |
| install ipa on iphone | – | – | yes ("3 Steps") | yes |
| sideload games | – | – | – | partly |
| ipa store / livecontainer / sidestore | – | – | – | yes (steps + guides) |

**Gap:** "sideload games on iPhone" and "ipa download" are not in any heading. Add one to the "3 Steps" H2 or the intro, for example "How to Sideload IPA Games on Your iPhone in 3 Steps".

---

## 4. Off-page and technical checklist

1. Verify the domain in **Google Search Console** and **Bing Webmaster Tools**, submit `https://ipagame.store/sitemap.xml`.
2. Confirm `robots.txt` and the sitemap on the **live** domain use `https://ipagame.store` (the local output shows `localhost`).
3. Test the live home page in **PageSpeed Insights** (mobile). Watch LCP (hero image and fonts) and CLS (missing image sizes).
4. Create the social profiles from H1 and link them.
5. Build a few honest backlinks: Reddit communities about sideloading, iOS forums, and directories. Share the new LiveContainer + SideStore guide, since guides earn links more easily than a home page.
6. Keep updating: the sitemap `lastmod` and the "Updated" dates already help freshness. Publish new guides regularly.
7. Rich-result and schema check with Google's Rich Results Test once live.

---

## 5. Suggested order of work

1. **H1** real social links + `sameAs` (5 minutes).
2. **H2** image width/height on filmstrip, shelves, posters (30 minutes).
3. **M1/M2** complete OG and Twitter tags (10 minutes).
4. **M5** better anchor text for the "See all" links (10 minutes).
5. **H3** fonts: trim or self-host.
6. **M4** `ItemList` schema for Top 10.
7. Off-page work (section 4).
