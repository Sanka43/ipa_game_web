# Guide Page Content – LiveContainer + SideStore

Source: sinceohsix's gist (LiveContainer + SideStore guide v2.0, last edited Mar 30, 2026). The text below is **rewritten in our own words** for ipagame.store, with credit and links to the original tools. Nothing in the site code has been changed yet.

---

## 1. Catalog entry (`app/catalog.php` → `guides()`)

| Field | Value |
|-------|-------|
| slug | `install-ipa-with-livecontainer` |
| topic | `Install` |
| title | How to Install LiveContainer + SideStore (Bypass the 3-App Limit) |
| short | Install LiveContainer + SideStore |
| desc | Step-by-step LiveContainer + SideStore setup with iloader and LocalDevVPN: sideload IPAs, refresh on-device, and run unlimited apps past the free 3-app limit. |

## 2. File: `content/guides/install-ipa-with-livecontainer.php`

### `$related`
`install-ipa-with-sidestore`, `install-ipa-without-computer`, `ipa-installation-failed`

### `$howto` (HowTo schema steps)
1. **Prepare your devices** – Install iTunes (Windows only) and iloader on the computer, and LocalDevVPN on the iPhone or iPad.
2. **Install SideStore with iloader** – Sign in with your Apple ID in iloader, connect the device, choose it and pick SideStore (Stable).
3. **Trust and enable Developer Mode** – Trust your Apple ID in VPN & Device Management and turn on Developer Mode (iOS 16+).
4. **Connect LocalDevVPN and refresh SideStore** – Connect the VPN, open SideStore and refresh SideStore from My Apps.
5. **Install LiveContainer + SideStore** – Download the LiveContainer+SideStore IPA, add it in SideStore and choose "Keep App Extensions (Use Main Profile)".
6. **Link the pairing file and clean up** – Select the pairing file inside LiveContainer, refresh it, delete the standalone SideStore, then import the certificate and run the JIT-Less test.

### `$faq`
- **What is LiveContainer?** – An app that runs other iOS apps inside itself without installing each one separately. Apps run inside LiveContainer do not use up your free-account app slots.
- **Why use LiveContainer with SideStore?** – A free Apple ID allows only 3 sideloaded apps. LiveContainer + SideStore takes 1 slot and lets you run as many apps as you want inside it.
- **Do I still need to refresh every 7 days?** – Yes. LiveContainer and any app installed through SideStore expire after 7 days on a free Apple ID. Turn on LocalDevVPN and refresh in SideStore, or automate it with Shortcuts.
- **Does it need a computer?** – Only for the first setup with iloader. After that, everything happens on the device.
- **Does it work on iOS 26.4?** – Not yet with this method. iOS 26.4 changed how refreshing works, so people on 26.4 should wait for an updated guide. The steps below cover iOS 15.0 – 26.3.
- **Stable or Nightly?** – Stable is the recommended release. Nightly has newer features (such as sources inside LiveContainer) but can be buggier.

### Article body (HTML/PHP-ready text)

**Intro paragraph**

**LiveContainer + SideStore** is the most flexible way to sideload on iPhone and iPad without paying for a developer account. SideStore installs and refreshes apps on the device. LiveContainer runs other apps *inside itself*, so they do not count toward the free 3-app limit. Set it up once and you can keep as many games as your storage allows.

> **Note (info box):** This guide supports **iOS 15.0 – 26.3**. iOS 26.4 broke the refresh method used here, so wait for an updated guide if you are on 26.4. Setup tools change often, so check the official [SideStore docs](https://docs.sidestore.io) and [LiveContainer docs](https://livecontainer.github.io/docs/intro) if anything looks different.

> **Note (warn box):** This guide assumes SideStore and LiveContainer are **not** installed yet. If you already have them, delete both first to avoid conflicts.

**H2: What are SideStore and LiveContainer?**
SideStore installs `.ipa` files on your device and refreshes them using a local VPN. LiveContainer runs iOS apps without installing them one by one. Because a free Apple ID allows only 3 installed apps, a special LiveContainer build that includes SideStore is used: 1 slot for LiveContainer, 1 spare slot, and unlimited apps inside LiveContainer.

The method uses community-made tools and Apple-approved sideloading, and needs a computer only for the initial setup.

**H2: What you need**
- **Computer:** iTunes (Windows only) and [iloader](https://github.com/nab138/iloader/releases).
- **iPhone / iPad:** [LocalDevVPN](https://apps.apple.com/us/app/localdevvpn/id6755608044) from the App Store.
- An Apple ID (a separate one for sideloading is recommended) and a USB cable.

**H2: Step 1: Install SideStore with iloader**
1. Open iloader and choose **Add Account +**, then sign in with your Apple ID.
2. Plug in your iPhone or iPad. Press **Refresh Devices** if it does not show up.
3. Under **Devices**, select your device.
4. Under **Installers**, choose **SideStore (Stable)** and wait for the install to finish.

**H2: Step 2: Set up SideStore**
1. Go to *Settings › General › VPN & Device Management*, tap your Apple ID email and **Trust** it.
2. Go to *Settings › Privacy & Security*, scroll to *Security* and turn on **Developer Mode** (iOS 16+). Follow the prompts and let the device restart.
3. Open **LocalDevVPN** and tap **Connect**. Allow the VPN configuration and enter your passcode.
   - *Info box:* LocalDevVPN is required for SideStore to install and refresh apps. Always connect it first.
4. Open **SideStore › My Apps** and tap **7 DAYS** next to SideStore.
5. Sign in with the same Apple ID you used in iloader.

SideStore now works as a normal sideloading app and also supports AltStore sources. If you only want SideStore, you can stop here, but you will have just 2 free slots. Continue to get LiveContainer.

**H2: Step 3: Switch to LiveContainer + SideStore**
1. On the device, download the latest **LiveContainer + SideStore** IPA from the [LiveContainer download](https://www.livecontainer.site/download/) page (Stable recommended, Nightly for testers).
2. In SideStore, open **My Apps**, tap **+** (top-left) and select the downloaded `LiveContainer+SideStore.ipa`.
3. When prompted, choose **Keep App Extensions (Use Main Profile)**. Wait for the install, then open LiveContainer.
4. Tap the **SideStore icon** (top-left) in LiveContainer. When asked for the pairing file, go to *On My iPhone/iPad › SideStore* and pick `ALTPairingFile.mobiledevicepairing`.
5. Open **My Apps** and tap **7 DAYS** next to LiveContainer. Sign in with the same Apple ID and wait for the refresh.
6. **Delete the standalone SideStore app.**
7. Close and reopen LiveContainer. Go to **Settings › Import Certificate From SideStore**, then confirm.
8. Tap **JIT-Less Mode Diagnose › Test JIT-Less Mode**. The test should pass.

**H2: How to use it**
- Apps installed **inside LiveContainer** do **not** count toward the 3-app limit.
- Apps installed **with SideStore** (even from inside LiveContainer) **do** count toward the limit.
- Turn on LocalDevVPN before installing or refreshing.
- LiveContainer (Nightly) can also load AltStore-style sources inside the app.
- Refresh every **7 days**. You can automate this with the Shortcuts app and Automations.

**H2: Troubleshooting**
- **Can't install / refresh:** LocalDevVPN is off or another VPN is active. Turn it on and disable other VPNs.
- **Pairing file not found:** open the *Files* app › *On My iPhone › SideStore*. If missing, redo Step 1 with iloader.
- **JIT-Less test fails:** re-run *Import Certificate From SideStore*, then test again.
- **Stuck on iOS 26.4:** this method does not work yet; wait for an updated guide.
- **Support:** LiveContainer + SideStore bundles an older SideStore nightly, so ask for help in the LiveContainer GitHub issues or the #support-forum on the SideStore Discord, **not** the normal SideStore channels.
- More errors: see [why an IPA installation fails](/guides/ipa-installation-failed/).

**H2: Credits and links**
Tools are built by volunteers, so please support them. Original guide by *eli* (sinceohsix), proof-read by suprstarrd.
- [SideStore docs](https://docs.sidestore.io) · [SideStore GitHub](https://github.com/SideStore/SideStore) · [SideStore site](https://sidestore.io)
- [LiveContainer docs](https://livecontainer.github.io/docs/intro) · [LiveContainer GitHub](https://github.com/LiveContainer/LiveContainer)
- [iloader](https://github.com/nab138/iloader)
- Original guide gist: https://gist.github.com/sinceohsix/688637ac04695d1ff38f844acc8ba7f3

---

## 3. Updates to existing guides (short additions)

| File | Addition |
|------|----------|
| `install-ipa-with-sidestore.php` | New "Want more than 3 apps?" section linking to the LiveContainer guide. Mention **iloader** and **LocalDevVPN** as the current installer and VPN app. Add the iOS 26.4 warning box. |
| `how-to-install-ipa-on-iphone.php` | Add a **Method 5: LiveContainer + SideStore** row/section in the comparison table (computer once, 7 days, unlimited apps inside LiveContainer, free). |
| `install-ipa-without-computer.php`, `sideload-games-on-iphone.php` | One-line link to the LiveContainer guide. |
| `routes/guides.php` | Update the meta description to mention LiveContainer. |

## 4. Implementation plan (waiting for your OK)

1. Add the `install-ipa-with-livecontainer` entry to `guides()` in `app/catalog.php` (goes into the "Install IPA files" group automatically).
2. Create `content/guides/install-ipa-with-livecontainer.php` from section 2 above, using the same helpers as the other guides (`note()`, `guide_url()`, `$howto`, `$faq`, `$related`).
3. Make the small edits from section 3.
4. Check `/guides/` and `/guides/install-ipa-with-livecontainer/` in the browser (TOC, HowTo/FAQ schema, mobile view). The sitemap picks the guide up if it reads `guides()`; I will verify that.
